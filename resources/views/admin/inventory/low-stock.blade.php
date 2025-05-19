@extends('layouts.admin')

@section('title', 'Low Stock Products')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Low Stock Products</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li>
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
                <a href="{{ route('inventory.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Inventory
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(count($lowStockItems) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockItems as $item)
                                <tr class="table-warning">
                                    <td>{{ $item->inventory_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->product_name }}" width="40" class="me-2">
                                            @else
                                                <div class="bg-light text-center rounded me-2" style="width: 40px; height: 40px; line-height: 40px;">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                            @endif
                                            {{ $item->product->product_name }}
                                        </div>
                                    </td>
                                    <td>{{ $item->product->category->category_name ?? 'Uncategorized' }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td>{{ $item->product->reorder_level ?? 10 }}</td>
                                    <td>{{ $item->last_updated ? $item->last_updated->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#adjustStockModal"
                                                    data-product-id="{{ $item->product->product_id }}"
                                                    data-product-name="{{ $item->product->product_name }}"
                                                    data-product-quantity="{{ $item->quantity }}"
                                                    data-product-unit="{{ $item->product->unit ?? 'units' }}">
                                                <i class="bi bi-plus-slash-minus"></i>
                                            </button>
                                            <a href="{{ route('products.edit', $item->product->product_id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('products.show', $item->product->product_id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
                    No low stock items found. Your inventory is in good shape!
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
                <h5 class="modal-title" id="adjustStockModalLabel">Adjust Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adjustStockForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p>Adjusting stock for: <strong id="productNameText"></strong></p>
                    <p>Current stock: <span id="currentStockText"></span></p>
                    
                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type</label>
                        <select class="form-select" id="adjustment_type" name="adjustment_type" required>
                            <option value="add">Add Stock (Received new inventory)</option>
                            <option value="remove">Remove Stock (Damaged/Lost/Used)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Adjust Stock Modal Handler
        const adjustStockModal = document.getElementById('adjustStockModal');
        if (adjustStockModal) {
            adjustStockModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');
                const productQuantity = button.getAttribute('data-product-quantity');
                const productUnit = button.getAttribute('data-product-unit');
                
                document.getElementById('productNameText').textContent = productName;
                document.getElementById('currentStockText').textContent = `${productQuantity} ${productUnit}`;
                
                const form = this.querySelector('#adjustStockForm');
                form.action = `/inventory/adjust/${productId}`;
                
                const quantityInput = form.querySelector('#quantity');
                quantityInput.min = 1;
                
                const adjustmentType = form.querySelector('#adjustment_type');
                adjustmentType.addEventListener('change', function() {
                    if (this.value === 'remove') {
                        quantityInput.max = productQuantity;
                    } else {
                        quantityInput.removeAttribute('max');
                    }
                });
            });
        }
    });
</script>
@endsection
@endsection