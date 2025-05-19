@extends('layouts.admin')

@section('title', 'Dental Services')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dental Services Management</h1>
        <div>
            <a href="{{ route('services.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New Service
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('services.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search services..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Service Name</th>
                            <th>Category</th>
                            <th>Duration</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>
                                    <a href="{{ route('services.show', $service->service_id) }}">
                                        {{ $service->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($service->category)
                                        <span class="badge bg-light text-dark">{{ $service->category }}</span>
                                    @else
                                        <span class="text-muted">Uncategorized</span>
                                    @endif
                                </td>
                                <td>
                                    @if($service->standard_duration)
                                        {{ $service->standard_duration }} min
                                    @else
                                        <span class="text-muted">Varies</span>
                                    @endif
                                </td>
                                <td>${{ number_format($service->standard_cost, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('services.show', $service->service_id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('services.edit', $service->service_id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="if(confirm('Are you sure you want to delete this service?')) { 
                                            document.getElementById('delete-service-{{ $service->service_id }}').submit(); 
                                        }">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-service-{{ $service->service_id }}" action="{{ route('services.destroy', $service->service_id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
                                        <h5>No services found</h5>
                                        <p class="text-muted">No dental services match your criteria.</p>
                                        <a href="{{ route('services.create') }}" class="btn btn-primary mt-2">Add New Service</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($services->count() > 0)
            <div class="card-footer bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} services</p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end">
                            {{ $services->links('vendor.pagination.custom-theme') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Service Statistics Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Active Services</h6>
                            <h3 class="mb-0">{{ \App\Models\DentalService::where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('services.index', ['status' => 'active']) }}" class="btn btn-sm btn-outline-success w-100">View Active</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-dash-circle fs-4 text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Inactive Services</h6>
                            <h3 class="mb-0">{{ \App\Models\DentalService::where('is_active', false)->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('services.index', ['status' => 'inactive']) }}" class="btn btn-sm btn-outline-secondary w-100">View Inactive</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-tag fs-4 text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Categories</h6>
                            <h3 class="mb-0">{{ $categories->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-info w-100">Manage Categories</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-cash-stack fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Avg. Price</h6>
                            <h3 class="mb-0">${{ number_format(\App\Models\DentalService::avg('standard_cost') ?? 0, 2) }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('services.index') }}" class="btn btn-sm btn-outline-primary w-100">View Services</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection