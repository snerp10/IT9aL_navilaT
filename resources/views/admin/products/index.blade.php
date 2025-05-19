@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Product Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Products</li>
    </ol>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-box-seam me-1"></i>
                All Products
            </div>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <form class="d-flex gap-2" action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search products..." name="search" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        
                        <select class="form-select w-auto" name="category" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select class="form-select w-auto" name="supplier" onchange="this.form.submit()">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('products.low-stock') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-exclamation-triangle"></i> Low Stock
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-info btn-sm">
                        <i class="bi bi-tags"></i> Categories
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-truck"></i> Suppliers
                    </a>
                </div>
            </div>
            
            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Stock</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->product_id }}</td>
                                <td>{{ $product->sku ?? 'N/A' }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                <td>₱{{ number_format($product->cost_price, 2) }}</td>
                                <td>₱{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if(isset($product->inventory))
                                        <span class="badge {{ $product->inventory->quantity > 10 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->inventory->quantity ?? 0 }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No Stock</span>
                                    @endif
                                </td>
                                <td>{{ $product->expiration_date ? $product->expiration_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" title="Add Stock" data-bs-toggle="modal" data-bs-target="#addStockModal" data-product-id="{{ $product->product_id }}" data-product-name="{{ $product->product_name }}">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-product-id="{{ $product->product_id }}" data-product-name="{{ $product->product_name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('inventory.adjust', $product->inventory->inventory_id ?? $product->product_id) }}">
                    @csrf
                    <input type="hidden" name="adjustment_type" value="add">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="{{ route('products.destroy', 0) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="product_id" id="delete_product_id" value="">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupStockModal(modalId, formId, productIdField, quantityField, notesField) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name') || '';
            this.querySelector('.modal-title').textContent = `Add Stock for ${productName}`;
            document.getElementById(productIdField).value = productId;
            const form = this.querySelector(`#${formId}`);
            form.setAttribute('action', `/inventory/adjust/${productId}`);
        });
        const form = document.getElementById(formId);
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productId = form.querySelector(`#${productIdField}`).value;
            const quantity = form.querySelector(`#${quantityField}`).value;
            const notes = form.querySelector(`#${notesField}`).value;
            window.location.href = `/inventory/adjust/${productId}?adjustment_type=add&quantity=${quantity}&notes=${encodeURIComponent(notes)}`;
        });
    }
    setupStockModal('addStockModal', 'addStockForm', 'stock_product_id', 'quantity', 'notes');
    setupStockModal('adjustStockModal', 'adjustStockForm', 'modal_product_id', 'quantity', 'notes');
});
</script>
@endsection
@endsection
