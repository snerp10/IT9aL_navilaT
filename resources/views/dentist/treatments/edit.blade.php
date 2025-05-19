@extends('layouts.dentist')

@section('title', 'Edit Treatment')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Treatment</h1>
        <div>
            <a href="{{ route('dentist.treatments.show', $treatment) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Treatment
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Treatment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dentist.treatments.update', $treatment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading">Patient: {{ $treatment->patient->first_name }} {{ $treatment->patient->last_name }}</h6>
                            <p class="mb-0 small">Appointment: {{ $treatment->appointment->appointment_date->format('M d, Y g:i A') }}</p>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="service_id" class="form-label">Dental Service</label>
                                <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id">
                                    <option value="">Custom Treatment</option>
                                    @foreach($dentalServices as $service)
                                        <option value="{{ $service->service_id }}" {{ $treatment->service_id == $service->service_id ? 'selected' : '' }}>
                                            {{ $service->name }} - ${{ number_format($service->standard_cost, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select a predefined service or leave as "Custom Treatment" to enter details manually</div>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Treatment Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name', $treatment->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="cost" class="form-label">Cost ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" required value="{{ old('cost', $treatment->cost) }}">
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tooth_number" class="form-label">Tooth Number (1-32, if applicable)</label>
                                <input type="number" class="form-control @error('tooth_number') is-invalid @enderror" id="tooth_number" name="tooth_number" min="1" max="32" value="{{ old('tooth_number', $treatment->tooth_number) }}">
                                @error('tooth_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Duration (minutes)</label>
                                <input type="number" min="1" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $treatment->duration) }}">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $treatment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Clinical Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $treatment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Planned" {{ old('status', $treatment->status) == 'Planned' ? 'selected' : '' }}>Planned</option>
                                <option value="In Progress" {{ old('status', $treatment->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ old('status', $treatment->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Treatment</button>
                            <a href="{{ route('dentist.treatments.show', $treatment) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dental service selection
        const serviceSelect = document.getElementById('service_id');
        const treatmentNameInput = document.getElementById('name');
        const descriptionTextarea = document.getElementById('description');
        const costInput = document.getElementById('cost');
        const durationInput = document.getElementById('duration');
        
        serviceSelect.addEventListener('change', function() {
            const serviceId = this.value;
            
            if (!serviceId) {
                // Don't clear fields if "Custom Treatment" is selected during edit
                return;
            }
            
            // Only update fields if they select a different service
            if (serviceId != "{{ $treatment->service_id }}") {
                // Fetch service details via AJAX
                fetch(`{{ route('dentist.treatments.service-details') }}?service_id=${serviceId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Service details not found');
                        }
                        return response.json();
                    })
                    .then(data => {
                        treatmentNameInput.value = data.name;
                        descriptionTextarea.value = data.description;
                        costInput.value = data.cost;
                        durationInput.value = data.duration;
                    })
                    .catch(error => {
                        console.error('Error fetching service details:', error);
                    });
            }
        });
    });
</script>
@endpush
@endsection