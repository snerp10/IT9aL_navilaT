@extends('layouts.admin')

@section('title', 'Low Stock Products')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Low Stock Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Low Stock</li>
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
                <i class="bi bi-exclamation-triangle me-1"></i>
                Low Stock Products
            </div>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Products
                </a>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-archive"></i> Inventory
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(count($products) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="table-warning">
                                    <td>{{ $product->product_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}" width="40" class="me-2">
                                            @else
                                                <div class="bg-light text-center rounded me-2" style="width: 40px; height: 40px; line-height: 40px;">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                            @endif
                                            {{ $product->product_name }}
                                        </div>
                                    </td>
                                    <td>{{ $product->category->category_name ?? 'Uncategorized' }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $product->inventory->quantity ?? 0 }}</span>
                                    </td>
                                    <td>{{ $product->reorder_level ?? 10 }}</td>
                                    <td>₱{{ number_format($product->selling_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" title="Add Stock" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#adjustStockModal" 
                                                    data-product-id="{{ $product->product_id }}"
                                                    data-inventory-id="{{ $product->inventory->inventory_id ?? '' }}"
                                                    data-product-name="{{ $product->product_name }}"
                                                    data-product-quantity="{{ $product->inventory->quantity ?? 0 }}">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No low stock products found. Your inventory is in good shape!
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-labelledby="adjustStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adjustStockModalLabel">Add Stock</h5>
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