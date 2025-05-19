@extends('layouts.admin')

@section('title', 'Edit Treatment')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Treatment</h1>
        <div>
            <a href="{{ route('treatments.show', $treatment->treatment_id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Treatment
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Treatment Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('treatments.update', $treatment->treatment_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="patient_id" class="form-label">Patient</label>
                        <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required {{ $treatment->billings->count() > 0 ? 'disabled' : '' }}>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->patient_id }}" {{ $treatment->patient_id == $patient->patient_id ? 'selected' : '' }}>
                                    {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                                </option>
                            @endforeach
                        </select>
                        @if($treatment->billings->count() > 0)
                            <input type="hidden" name="patient_id" value="{{ $treatment->patient_id }}">
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Patient cannot be changed because this treatment has associated billing records.
                            </div>
                        @endif
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="appointment_id" class="form-label">Appointment</label>
                        <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id" name="appointment_id" required {{ $treatment->billings->count() > 0 ? 'disabled' : '' }}>
                            <option value="">Select Appointment</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->appointment_id }}" 
                                    data-patient-id="{{ $appointment->patient_id }}"
                                    {{ $treatment->appointment_id == $appointment->appointment_id ? 'selected' : '' }}>
                                    {{ $appointment->appointment_date->format('M d, Y g:i A') }} - 
                                    Dr. {{ $appointment->dentist->first_name }} {{ $appointment->dentist->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @if($treatment->billings->count() > 0)
                            <input type="hidden" name="appointment_id" value="{{ $treatment->appointment_id }}">
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                Appointment cannot be changed because this treatment has associated billing records.
                            </div>
                        @endif
                        @error('appointment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dentist_id" class="form-label">Dentist</label>
                        <select class="form-select @error('dentist_id') is-invalid @enderror" id="dentist_id" name="dentist_id" required>
                            <option value="">Select Dentist</option>
                            @foreach(App\Models\Employee::where('role', 'Dentist')->get() as $dentist)
                                <option value="{{ $dentist->employee_id }}" {{ $treatment->dentist_id == $dentist->employee_id ? 'selected' : '' }}>{{ $dentist->first_name }} {{ $dentist->last_name }}</option>
                            @endforeach
                        </select>
                        @error('dentist_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="service_id" class="form-label">Dental Service</label>
                        <select class="form-select" id="service_id" name="service_id">
                            <option value="">Custom Treatment</option>
                            @foreach($dentalServices as $service)
                                <option value="{{ $service->service_id }}" {{ $treatment->service_id == $service->service_id ? 'selected' : '' }}>
                                    {{ $service->name }} - ${{ number_format($service->standard_cost, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Select a predefined service or leave as "Custom Treatment" to enter details manually</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tooth_number" class="form-label">Tooth Number (1-32, if applicable)</label>
                        <input type="number" class="form-control @error('tooth_number') is-invalid @enderror" id="tooth_number" name="tooth_number" min="1" max="32" value="{{ $treatment->tooth_number }}">
                        @error('tooth_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="name" class="form-label">Treatment Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ $treatment->name }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ $treatment->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="cost" class="form-label">Cost ($)</label>
                        <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" required value="{{ $treatment->cost }}">
                        @error('cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="duration" class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ $treatment->duration }}">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="Planned" {{ $treatment->status == 'Planned' ? 'selected' : '' }}>Planned</option>
                            <option value="In Progress" {{ $treatment->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ $treatment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="notes" class="form-label">Clinical Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ $treatment->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('treatments.show', $treatment->treatment_id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Treatment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle appointment selection affecting patient
        const appointmentSelect = document.getElementById('appointment_id');
        const patientSelect = document.getElementById('patient_id');
        
        appointmentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.patientId) {
                patientSelect.value = selectedOption.dataset.patientId;
            }
        });
        
        // Handle dental service selection
        const serviceSelect = document.getElementById('service_id');
        const treatmentNameInput = document.getElementById('name');
        const descriptionTextarea = document.getElementById('description');
        const costInput = document.getElementById('cost');
        const durationInput = document.getElementById('duration');
        
        serviceSelect.addEventListener('change', function() {
            const serviceId = this.value;
            
            if (!serviceId) {
                // No need to clear fields when editing - just keep existing values
                return;
            }
            
            // Fetch service details via AJAX
            fetch(`{{ route('treatments.service-details') }}?service_id=${serviceId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Ask user before overwriting existing data
                    if (confirm('Do you want to overwrite the current treatment details with the selected service information?')) {
                        // Populate form fields with service data
                        treatmentNameInput.value = data.name;
                        descriptionTextarea.value = data.description;
                        costInput.value = data.cost;
                        durationInput.value = data.duration;
                    }
                })
                .catch(error => {
                    console.error('Error fetching service details:', error);
                    alert('Could not load service details. Please try again or enter details manually.');
                });
        });
    });
</script>
@endpush
@endsection