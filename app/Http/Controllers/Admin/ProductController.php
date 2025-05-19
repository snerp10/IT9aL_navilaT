<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with(['category', 'inventory'])->paginate(10);
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,category_id',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'exists:suppliers,supplier_id',
            'initial_quantity' => 'nullable|integer|min:0',
        ]);

        $product = Product::create($validated);

        // Associate suppliers if provided
        if (isset($validated['supplier_ids'])) {
            $product->suppliers()->attach($validated['supplier_ids'], ['date_supplied' => now()]);
        }

        // Create inventory record if initial quantity is provided
        if (isset($validated['initial_quantity']) && $validated['initial_quantity'] > 0) {
            $product->inventory()->create([
                'quantity' => $validated['initial_quantity'],
                'stock_status' => 'Stock In',
                'last_updated' => now(),
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'inventory']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $product->load('suppliers');
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,category_id',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'exists:suppliers,supplier_id',
        ]);

        $product->update($validated);

        // Update suppliers if provided
        if (isset($validated['supplier_ids'])) {
            $product->suppliers()->sync($validated['supplier_ids']);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Check if there's inventory for this product
        if ($product->inventory && $product->inventory->quantity > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product with existing inventory.');
        }

        // Detach all suppliers
        $product->suppliers()->detach();
        
        // Delete inventory record if exists
        if ($product->inventory) {
            $product->inventory->delete();
        }
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Display products by category.
     */
    public function byCategory(Category $category)
    {
        $products = Product::where('category_id', $category->category_id)
            ->with(['inventory', 'suppliers'])
            ->get();
            
        return view('admin.products.by-category', compact('products', 'category'));
    }

    /**
     * Display products by supplier.
     */
    public function bySupplier(Supplier $supplier)
    {
        $products = $supplier->products()->with(['category', 'inventory'])->get();
        return view('admin.products.by-supplier', compact('products', 'supplier'));
    }

    /**
     * Display low stock products.
     */
    public function lowStock()
    {
        $products = Product::whereHas('inventory', function ($query) {
            $query->where('quantity', '<', 10); // Assuming 10 is the threshold for low stock
        })->with(['category', 'inventory'])->get();
        
        return view('admin.products.low-stock', compact('products'));
    }

    /**
     * Import products from CSV file
     */
    public function import(Request $request)
    {
        // Validate the uploaded file
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();

            // Read the CSV file
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle); // Get header row
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Map CSV headers to database fields
            $headerMap = [
                'product_name' => array_search('name', array_map('strtolower', $header)),
                'description' => array_search('description', array_map('strtolower', $header)),
                'cost_price' => array_search('cost_price', array_map('strtolower', $header)),
                'selling_price' => array_search('selling_price', array_map('strtolower', $header)),
                'expiration_date' => array_search('expiration_date', array_map('strtolower', $header)),
                'category' => array_search('category', array_map('strtolower', $header)),
                'quantity' => array_search('quantity', array_map('strtolower', $header)),
            ];

            // Process each row in the CSV
            while (($data = fgetcsv($handle)) !== false) {
                // Skip empty rows
                if (count(array_filter($data)) === 0) continue;

                // Prepare data for validation
                $rowData = [
                    'product_name' => $headerMap['product_name'] !== false ? $data[$headerMap['product_name']] : null,
                    'description' => $headerMap['description'] !== false ? $data[$headerMap['description']] : null,
                    'cost_price' => $headerMap['cost_price'] !== false ? $data[$headerMap['cost_price']] : null,
                    'selling_price' => $headerMap['selling_price'] !== false ? $data[$headerMap['selling_price']] : null,
                    'expiration_date' => $headerMap['expiration_date'] !== false ? $data[$headerMap['expiration_date']] : null,
                    'category' => $headerMap['category'] !== false ? $data[$headerMap['category']] : null,
                    'quantity' => $headerMap['quantity'] !== false ? $data[$headerMap['quantity']] : null,
                ];

                // Validate row data
                $validator = Validator::make($rowData, [
                    'product_name' => 'required|string|max:255',
                    'cost_price' => 'required|numeric|min:0',
                    'selling_price' => 'required|numeric|min:0',
                    'category' => 'required|string',
                ]);

                if ($validator->fails()) {
                    $errorCount++;
                    $errors[] = "Row " . ($successCount + $errorCount) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Find or create category
                $category = Category::firstOrCreate(
                    ['name' => $rowData['category']],
                    ['description' => 'Imported category']
                );

                // Create product
                $product = Product::create([
                    'product_name' => $rowData['product_name'],
                    'description' => $rowData['description'],
                    'cost_price' => $rowData['cost_price'],
                    'selling_price' => $rowData['selling_price'],
                    'expiration_date' => $rowData['expiration_date'],
                    'category_id' => $category->id,
                ]);

                // Create inventory record if quantity is provided
                if (!empty($rowData['quantity']) && is_numeric($rowData['quantity'])) {
                    $product->inventory()->create([
                        'quantity' => $rowData['quantity'],
                        'stock_status' => $rowData['quantity'] > 0 ? 'Stock In' : 'Stock Out',
                        'last_updated' => now(),
                    ]);
                }

                $successCount++;
            }

            fclose($handle);

            // Return with appropriate message
            if ($errorCount > 0) {
                return redirect()->route('inventory.index')
                    ->with('warning', "Import completed with {$successCount} products imported and {$errorCount} errors.")
                    ->with('import_errors', $errors);
            } else {
                return redirect()->route('inventory.index')
                    ->with('success', "{$successCount} products imported successfully.");
            }

        } catch (\Exception $e) {
            Log::error('Product import error: ' . $e->getMessage());
            return redirect()->route('inventory.index')
                ->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }
}