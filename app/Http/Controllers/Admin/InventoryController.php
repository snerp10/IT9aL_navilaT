<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory.
     */
    public function index(Request $request)
    {
        // Apply filters if any
        $query = Product::with(['inventory', 'category']);
        
        // Handle search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Handle category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }
        
        // Handle status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'in-stock') {
                $query->whereHas('inventory', function($q) {
                    $q->where('quantity', '>', 0)
                      ->where(function($sq) {
                          $sq->whereColumn('quantity', '>', 'reorder_level')
                             ->orWhereNull('reorder_level');
                      });
                });
            } elseif ($request->status == 'low-stock') {
                $query->whereHas('inventory', function($q) {
                    $q->where('quantity', '>', 0)
                      ->whereColumn('quantity', '<=', 'reorder_level');
                });
            } elseif ($request->status == 'out-of-stock') {
                $query->where(function($q) {
                    $q->whereHas('inventory', function($sq) {
                        $sq->where('quantity', '<=', 0);
                    })->orWhereDoesntHave('inventory');
                });
            }
        }
        
        // Get paginated results
        $products = $query->paginate(10)->withQueryString();
        
        // Get all categories for the filter dropdown
        $categories = Category::all();
        
        // Calculate inventory statistics
        $totalProducts = Product::count();
        $lowStockCount = Product::whereHas('inventory', function($query) {
            $query->where('quantity', '>', 0)
                  ->whereColumn('quantity', '<=', 'reorder_level');
        })->count();
        $inStockCount = Product::whereHas('inventory', function($query) {
            $query->where('quantity', '>', 0)
                  ->whereColumn('quantity', '>', 'reorder_level');
        })->count();
        $outOfStockCount = Product::whereHas('inventory', function($query) {
            $query->where('quantity', '<=', 0);
        })->orWhereDoesntHave('inventory')->count();
        
        // Create stats array to use in the view
        $stats = [
            'total_products' => $totalProducts,
            'low_stock' => $lowStockCount,
            'in_stock' => $inStockCount,
            'out_of_stock' => $outOfStockCount
        ];

        return view('admin.inventory.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new inventory entry.
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created inventory entry in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'stock_status' => 'required|in:Stock In,Stock Out',
            'quantity' => 'required|integer|min:0',
        ]);

        Inventory::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory entry created successfully.');
    }

    /**
     * Display the specified inventory entry.
     */
    public function show(Inventory $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified inventory entry.
     */
    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified inventory entry in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'stock_status' => 'required|in:Stock In,Stock Out',
            'quantity' => 'required|integer|min:0',
        ]);

        $validated['last_updated'] = now();

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory entry updated successfully.');
    }

    /**
     * Remove the specified inventory entry from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory entry deleted successfully.');
    }

    /**
     * Process a stock adjustment (add or remove inventory).
     */
    public function adjust(Request $request, $productId)
    {
        // Validate the request data
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            // Find the product's inventory record or create it if it doesn't exist
            $inventoryItem = Inventory::firstOrNew(['product_id' => $productId]);
            $isNew = !$inventoryItem->exists;
            
            // Set default values for new inventory records
            if ($isNew) {
                $inventoryItem->quantity = 0;
                $inventoryItem->reorder_level = 10; // Default reorder level
                $inventoryItem->stock_status = 'Stock Out';
            }

            // Store the original quantity for transaction logging
            $originalQuantity = $inventoryItem->quantity;
            
            // Calculate new quantity based on adjustment type
            if ($validated['adjustment_type'] === 'add') {
                $newQuantity = $originalQuantity + $validated['quantity'];
                $transactionType = 'Stock In';
            } elseif ($validated['adjustment_type'] === 'remove') {
                // Check if we have enough inventory to remove
                if ($originalQuantity < $validated['quantity']) {
                    return redirect()->back()
                        ->with('error', 'Cannot remove more items than are in stock.');
                }
                
                $newQuantity = $originalQuantity - $validated['quantity'];
                $transactionType = 'Stock Out';
            } else { // set exact value
                $newQuantity = $validated['quantity'];
                $transactionType = 'Adjustment';
            }

            // Update stock status based on new quantity and reorder level
            if ($newQuantity <= 0) {
                $stockStatus = 'Stock Out';
            } elseif ($newQuantity <= $inventoryItem->reorder_level) {
                $stockStatus = 'Low Stock';
            } else {
                $stockStatus = 'Stock In';
            }

            // Update the inventory record
            $inventoryItem->quantity = $newQuantity;
            $inventoryItem->stock_status = $stockStatus;
            $inventoryItem->last_updated = now();
            $inventoryItem->save();

            // Create an inventory transaction record
            InventoryTransaction::create([
                'inventory_id' => $inventoryItem->inventory_id,
                'product_id' => $productId,
                'transaction_type' => $transactionType,
                'quantity' => $validated['quantity'],
                'reference' => 'Manual adjustment',
                'notes' => $validated['notes'] ?? 'Quantity adjusted from ' . $originalQuantity . ' to ' . $newQuantity,
                'created_by' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('success', 'Inventory adjusted successfully. New quantity: ' . $newQuantity);
                
        } catch (\Exception $e) {
            \Log::error('Inventory adjustment error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error adjusting inventory: ' . $e->getMessage());
        }
    }

    /**
     * Display low stock inventory items.
     */
    public function lowStock()
    {
        $lowStockItems = Inventory::where('quantity', '<', 10) // Threshold for low stock
            ->with('product.category')
            ->get();
            
        return view('admin.inventory.low-stock', compact('lowStockItems'));
    }

    /**
     * Display out of stock inventory items.
     */
    public function outOfStock()
    {
        $outOfStockItems = Inventory::where('quantity', 0)
            ->orWhere('stock_status', 'Stock Out')
            ->with('product.category')
            ->get();
            
        return view('admin.inventory.out-of-stock', compact('outOfStockItems'));
    }

    /**
     * Export inventory data to CSV.
     */
    public function export()
    {
        try {
            $products = Product::with(['category', 'inventory'])->get();
            
            // Set the CSV headers
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="inventory_export_' . date('Y-m-d') . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];
            
            // Create the callback to generate the CSV content
            $callback = function() use ($products) {
                // Create a file handle for php://output
                $handle = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($handle, [
                    'ID',
                    'Product Name',
                    'Category',
                    'SKU',
                    'Description',
                    'Cost Price',
                    'Selling Price',
                    'Current Stock',
                    'Stock Status',
                    'Reorder Level',
                    'Last Updated'
                ]);
                
                // Add product data
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->product_id,
                        $product->product_name,
                        $product->category ? $product->category->category_name : 'Uncategorized',
                        $product->sku ?? 'N/A',
                        $product->description,
                        $product->cost_price,
                        $product->selling_price,
                        $product->inventory ? $product->inventory->quantity : 0,
                        $product->inventory ? $product->inventory->stock_status : 'No Inventory',
                        $product->inventory ? $product->inventory->reorder_level : 10,
                        $product->inventory ? $product->inventory->last_updated : 'N/A'
                    ]);
                }
                
                fclose($handle);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Inventory export error: ' . $e->getMessage());
            return redirect()->route('inventory.index')
                ->with('error', 'Error exporting inventory data: ' . $e->getMessage());
        }
    }
}