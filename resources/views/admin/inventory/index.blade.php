@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inventory Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Inventory</li>
    </ol>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Inventory Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Total Products</div>
                            <div class="h2 mb-0">{{ $stats['total_products'] }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('products.index') }}">View All Products</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Low Stock</div>
                            <div class="h2 mb-0">{{ $stats['low_stock'] }}</div>
                        </div>
                        <div class="text-dark">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-dark stretched-link" href="{{ route('inventory.low-stock') }}">View Low Stock Items</a>
                    <div class="small text-dark"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">In Stock</div>
                            <div class="h2 mb-0">{{ $stats['in_stock'] }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('inventory.index', ['status' => 'in-stock']) }}">View In Stock Items</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Out of Stock</div>
                            <div class="h2 mb-0">{{ $stats['out_of_stock'] }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-x-circle fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('inventory.out-of-stock') }}">View Out of Stock Items</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-archive me-1"></i>
                Current Inventory
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-arrow-up"></i> Import Products
                </button>
                <a href="{{ route('products.create') }}" class="btn btn-success btn-sm ms-2">
                    <i class="bi bi-plus-circle"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Options -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <form class="d-flex gap-2" action="{{ route('inventory.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search inventory..." name="search" value="{{ request('search') }}">
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
                        
                        <select class="form-select w-auto" name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="in-stock" {{ request('status') == 'in-stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low-stock" {{ request('status') == 'low-stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out-of-stock" {{ request('status') == 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="printInventory()">
                        <i class="bi bi-printer"></i> Print Inventory
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportInventory()">
                        <i class="bi bi-file-earmark-excel"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="{{ $product->inventory && $product->inventory->quantity <= 0 ? 'table-danger' : ($product->inventory && $product->inventory->quantity <= $product->inventory->reorder_level ? 'table-warning' : '') }}">
                                <td>{{ $product->product_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}" width="40" class="me-2">
                                        @else
                                            <div class="bg-light text-center rounded me-2" style="width: 40px; height: 40px;">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif
                                        {{ $product->product_name }}
                                    </div>
                                </td>
                                <td>{{ $product->sku ?? 'N/A' }}</td>
                                <td>{{ $product->category->category_name ?? 'Uncategorized' }}</td>
                                <td>
                                    <span class="fw-bold">{{ $product->inventory ? $product->inventory->quantity : 0 }} {{ $product->unit ?? 'units' }}</span>
                                </td>
                                <td>{{ $product->inventory ? $product->inventory->reorder_level : 10 }} {{ $product->unit ?? 'units' }}</td>
                                <td>
                                    @if(!$product->inventory || $product->inventory->quantity <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($product->inventory->quantity <= $product->inventory->reorder_level)
                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                                <td>{{ $product->inventory ? $product->inventory->updated_at->format('M d, Y H:i') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#adjustStockModal"
                                                data-product-id="{{ $product->product_id }}"
                                                data-product-name="{{ $product->product_name }}"
                                                data-product-quantity="{{ $product->inventory ? $product->inventory->quantity : 0 }}"
                                                data-product-unit="{{ $product->unit ?? 'units' }}">
                                            <i class="bi bi-plus-slash-minus"></i>
                                        </button>
                                        <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
                            <option value="set">Set Exact Value (Inventory count)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" step="1" required>
                        <div class="form-text" id="quantityHelp">Enter the quantity to adjust.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        <div class="form-text">Add any relevant notes about this adjustment.</div>
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

<!-- Import Products Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            CSV should include: name, sku, description, price, quantity, category, etc.
                            <a href="#">Download template</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
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
                form.action = `/inventory/${productId}/adjust`;
                
                const quantityInput = form.querySelector('#quantity');
                quantityInput.min = 0;
                quantityInput.value = 1;
                
                const adjustmentType = form.querySelector('#adjustment_type');
                adjustmentType.addEventListener('change', function() {
                    const help = document.getElementById('quantityHelp');
                    if (this.value === 'add') {
                        help.textContent = 'Enter quantity to add to inventory.';
                    } else if (this.value === 'remove') {
                        help.textContent = `Enter quantity to remove from inventory (max: ${productQuantity}).`;
                        quantityInput.max = productQuantity;
                    } else {
                        help.textContent = 'Enter exact inventory count.';
                        quantityInput.removeAttribute('max');
                    }
                });
            });
        }
    });
    
    function printInventory() {
        const printContents = document.getElementById('inventoryTable').outerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <h1 class="text-center">Inventory Report</h1>
            <p class="text-center">Generated: ${new Date().toLocaleString()}</p>
            <div class="container">${printContents}</div>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
    
    function exportInventory() {
        window.location.href = "{{ route('inventory.export') }}";
    }
</script>
@endsection
@endsection
