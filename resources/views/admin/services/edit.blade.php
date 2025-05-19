@extends('layouts.admin')

@section('title', 'Edit ' . $service->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Dental Service</h5>
                        <div>
                            <a href="{{ route('services.show', $service->service_id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('services.update', $service->service_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $service->category) }}" list="category-suggestions">
                                <datalist id="category-suggestions">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">
                                    @endforeach
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="standard_cost" class="form-label">Standard Cost ($) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" class="form-control @error('standard_cost') is-invalid @enderror" id="standard_cost" name="standard_cost" value="{{ old('standard_cost', $service->standard_cost) }}" required>
                                    @error('standard_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="standard_duration" class="form-label">Standard Duration (minutes)</label>
                                <div class="input-group">
                                    <input type="number" step="1" min="1" class="form-control @error('standard_duration') is-invalid @enderror" id="standard_duration" name="standard_duration" value="{{ old('standard_duration', $service->standard_duration) }}">
                                    <span class="input-group-text">minutes</span>
                                    @error('standard_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Service</label>
                            </div>
                            <div class="form-text">Inactive services won't appear in appointment booking options</div>
                        </div>
                        
                        @if($service->treatments->count() > 0)
                            <div class="alert alert-info d-flex align-items-center mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    This service is used in {{ $service->treatments->count() }} treatment(s). 
                                    Editing it will not affect existing treatments.
                                </div>
                            </div>
                        @endif
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection