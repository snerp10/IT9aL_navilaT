@extends('layouts.admin')

@section('title', 'Suppliers Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Suppliers Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Suppliers</li>
    </ol>
    
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Supplier List Card -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-truck me-1"></i>
                        All Suppliers
                    </div>
                    <div>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Supplier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Products</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->supplier_id }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>
                                            <a href="{{ route('products.index', ['supplier' => $supplier->supplier_id]) }}" class="badge bg-primary">
                                                {{ $supplier->products_count ?? 0 }} products
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('suppliers.show', $supplier->supplier_id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('suppliers.edit', $supplier->supplier_id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal" 
                                                    data-supplier-id="{{ $supplier->supplier_id }}" 
                                                    data-supplier-name="{{ $supplier->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No suppliers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-graph-up me-1"></i>
                    Supplier Statistics
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h6>Total Suppliers</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="h4">{{ $stats['total_suppliers'] }}</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h6>Products Supplied</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="h4">{{ $stats['total_products'] }}</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h6>Avg. Products per Supplier</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="h4">{{ $stats['avg_products'] }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> New Supplier
                        </a>
                        <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box"></i> New Product
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clock-history me-1"></i>
                    Recent Orders
                </div>
                <div class="card-body">
                    @if(count($recentOrders) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentOrders as $order)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $order->supplier->name }}</strong>
                                        <div class="text-muted small">{{ $order->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <span class="badge bg-info rounded-pill">₱{{ number_format($order->total_amount, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3 text-center">
                            <a href="#" class="text-decoration-none">View All Orders</a>
                        </div>
                    @else
                        <p class="text-center text-muted my-3">No recent orders found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Supplier Modal -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSupplierModalLabel">Delete Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete supplier <strong id="deleteSupplierName"></strong>?</p>
                <p class="text-danger">This will remove the supplier association from all products. Products will not be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSupplierForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete modal handler
        const deleteModal = document.getElementById('deleteSupplierModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const supplierId = button.getAttribute('data-supplier-id');
                const supplierName = button.getAttribute('data-supplier-name');
                
                document.getElementById('deleteSupplierName').textContent = supplierName;
                document.getElementById('deleteSupplierForm').action = `/suppliers/${supplierId}`;
            });
        }
    });
</script>
@endsection
@endsection
