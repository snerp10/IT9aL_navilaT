<?php

// Moved to Admin/SupplierController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        // Use paginate instead of get for pagination
        $suppliers = Supplier::withCount('products')->paginate(10);
        
        // Add statistics for the sidebar
        $stats = [
            'total_suppliers' => Supplier::count(),
            'total_products' => Product::count(),
            'avg_products' => Supplier::count() > 0 ? round(Product::count() / Supplier::count(), 1) : 0,
        ];
        
        // Get some recent orders (placeholder - replace with actual order data)
        $recentOrders = [];
        
        return view('admin.suppliers.index', compact('suppliers', 'stats', 'recentOrders'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:suppliers,email',
            'address' => 'required|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('products');
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->supplier_id . ',supplier_id',
            'address' => 'required|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Check if there are products associated with this supplier
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Cannot delete supplier with associated products. Remove product associations first.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}