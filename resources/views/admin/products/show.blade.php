@extends('layouts.admin')
@section('title', 'Product Details')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Product Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">{{ $product->product_name }}</li>
    </ol>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Product Details Card -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-box-seam me-1"></i>
                        Product Information
                    </div>
                    <div>
                        <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <h4>{{ $product->product_name }}</h4>
                            <h6 class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2">Basic Information</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Product ID:</dt>
                                <dd class="col-sm-8">{{ $product->product_id }}</dd>
                                
                                <dt class="col-sm-4">Category:</dt>
                                <dd class="col-sm-8">{{ $product->category->category_name ?? 'N/A' }}</dd>
                                
                                <dt class="col-sm-4">Cost Price:</dt>
                                <dd class="col-sm-8">₱{{ number_format($product->cost_price, 2) }}</dd>
                                
                                <dt class="col-sm-4">Selling Price:</dt>
                                <dd class="col-sm-8">₱{{ number_format($product->selling_price, 2) }}</dd>
                                
                                <dt class="col-sm-4">Profit Margin:</dt>
                                <dd class="col-sm-8">₱{{ number_format($product->selling_price - $product->cost_price, 2) }} 
                                    ({{ number_format((($product->selling_price - $product->cost_price) / $product->cost_price) * 100, 2) }}%)
                                </dd>
                                
                                <dt class="col-sm-4">Expiration:</dt>
                                <dd class="col-sm-8">
                                    @if($product->expiration_date)
                                        {{ $product->expiration_date->format('M d, Y') }}
                                        @if($product->expiration_date->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($product->expiration_date->diffInMonths(now()) < 3)
                                            <span class="badge bg-warning">Expiring Soon</span>
                                        @else
                                            <span class="badge bg-success">Valid</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2">Inventory Status</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Current Stock:</dt>
                                <dd class="col-sm-8">
                                    @if(isset($product->inventory))
                                        <span class="badge {{ $product->inventory->quantity > 10 ? 'bg-success' : ($product->inventory->quantity > 0 ? 'bg-warning' : 'bg-danger') }} me-2">
                                            {{ $product->inventory->quantity ?? 0 }} units
                                        </span>
                                        <small class="text-muted">
                                            Status: {{ $product->inventory->stock_status }}
                                        </small>
                                    @else
                                        <span class="badge bg-secondary">No Inventory Record</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-4">Last Updated:</dt>
                                <dd class="col-sm-8">
                                    {{ $product->inventory->last_updated->format('M d, Y g:i A') ?? 'N/A' }}
                                </dd>
                            </dl>
                            
                            <div class="mt-3 border-top pt-3">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addStockModal" data-product-id="{{ $product->product_id }}">
                                    <i class="bi bi-plus-circle"></i> Add Stock
                                </button>
                                
                                @if(isset($product->inventory) && $product->inventory->quantity > 0)
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reduceStockModal" data-product-id="{{ $product->product_id }}">
                                    <i class="bi bi-dash-circle"></i> Reduce Stock
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2">Product Description</h5>
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Suppliers Card -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-truck me-1"></i>
                    Suppliers
                </div>
                <div class="card-body">
                    @if($product->suppliers->isNotEmpty())
                        <div class="list-group">
                            @foreach($product->suppliers as $supplier)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $supplier->name }}</h6>
                                        <small>Last supplied: {{ $supplier->pivot->date_supplied ? \Carbon\Carbon::parse($supplier->pivot->date_supplied)->format('M d, Y') : 'Unknown' }}</small>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> {{ $supplier->contact_person }} |
                                        <i class="bi bi-telephone"></i> {{ $supplier->phone }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope"></i> {{ $supplier->email }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No suppliers associated with this product.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Stock History Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clock-history me-1"></i>
                    Inventory History
                </div>
                <div class="card-body">
                    <!-- Placeholder for inventory history -->
                    <div class="text-center py-3">
                        <i class="bi bi-list-check display-4 text-muted"></i>
                        <p class="text-muted mt-2">Coming soon: Inventory transaction history</p>
                    </div>
                </div>
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
            <form id="addStockForm" method="POST" action="{{ route('inventory.adjust', $product->product_id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reduce Stock Modal -->
<div class="modal fade" id="reduceStockModal" tabindex="-1" aria-labelledby="reduceStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reduceStockModalLabel">Reduce Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reduceStockForm" method="POST" action="{{ route('inventory.adjust', $product->product_id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reduce_quantity" class="form-label">Quantity to Remove</label>
                        <input type="number" class="form-control" id="reduce_quantity" name="quantity" min="1" max="{{ $product->inventory ? $product->inventory->quantity : 0 }}" required>
                        <input type="hidden" name="adjust_type" value="reduce">
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <select class="form-select" id="reason" name="reason">
                            <option value="used">Used in Treatment</option>
                            <option value="damaged">Damaged/Expired</option>
                            <option value="sold">Sold Separately</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reduction_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="reduction_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reduce Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add Stock Modal Handler
        const addStockModal = document.getElementById('addStockModal');
        if (addStockModal) {
            addStockModal.addEventListener('show.bs.modal', function(event) {
                // Nothing needed here as form action is already set
            });
        }
        
        // Reduce Stock Modal Handler
        const reduceStockModal = document.getElementById('reduceStockModal');
        if (reduceStockModal) {
            reduceStockModal.addEventListener('show.bs.modal', function(event) {
                // Nothing needed here as form action is already set
            });
        }
    });
</script>
@endsection
@endsection
