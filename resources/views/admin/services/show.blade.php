@extends('layouts.admin')

@section('title', $service->name)

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Service Details</h5>
                            <span class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($service->category)
                                <span class="badge bg-light text-dark">{{ $service->category }}</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('services.edit', $service->service_id) }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <h2 class="fw-bold mb-3">{{ $service->name }}</h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Standard Cost</p>
                            <h3 class="fw-bold text-primary">${{ number_format($service->standard_cost, 2) }}</h3>
                        </div>
                        
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Duration</p>
                            <h3 class="fw-bold">
                                @if($service->standard_duration)
                                    {{ $service->standard_duration }} minutes
                                @else
                                    <span class="text-muted fs-5">Not specified</span>
                                @endif
                            </h3>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-muted mb-2">Description</p>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0">{{ $service->description }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Created</p>
                            <p>{{ $service->created_at->format('M d, Y, g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Last Updated</p>
                            <p>{{ $service->updated_at->format('M d, Y, g:i A') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-danger" 
                        onclick="if(confirm('Are you sure you want to delete this service? This action cannot be undone.')) { 
                            document.getElementById('delete-service-form').submit(); 
                        }">
                        <i class="bi bi-trash"></i> Delete Service
                    </button>
                    <form id="delete-service-form" action="{{ route('services.destroy', $service->service_id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Usage Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-clipboard-check fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Treatment Count</h6>
                            <h3 class="mb-0">{{ $service->treatments->count() }}</h3>
                        </div>
                    </div>
                    
                    @if($service->treatments->count() > 0)
                        <hr>
                        <h6 class="mb-3">Recent Treatments</h6>
                        <div class="list-group list-group-flush">
                            @foreach($service->treatments->take(5) as $treatment)
                                <a href="{{ route('treatments.show', $treatment->treatment_id) }}" class="list-group-item list-group-item-action px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $treatment->treatment_name }}</h6>
                                        <small>${{ number_format($treatment->cost, 2) }}</small>
                                    </div>
                                    <p class="mb-1 small text-truncate">{{ Str::limit($treatment->description, 40) }}</p>
                                    <small class="text-muted">{{ $treatment->created_at->format('M d, Y') }}</small>
                                </a>
                            @endforeach
                        </div>
                        
                        @if($service->treatments->count() > 5)
                            <div class="mt-3 text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">View All Treatments</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection