@extends('layouts.admin')

@section('title', 'Add New Treatment')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add New Treatment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('treatments.store') }}" method="POST">
                        @csrf
                        
                        @if(request()->has('from_appointment'))
                            <input type="hidden" name="from_appointment" value="1">
                        @endif
                        
                        @if(request()->has('from_patient'))
                            <input type="hidden" name="from_patient" value="1">
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patient_id" class="form-label">Patient</label>
                                <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->patient_id }}" {{ $selectedPatientId == $patient->patient_id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="appointment_id" class="form-label">Appointment</label>
                                <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id" name="appointment_id" required>
                                    <option value="">Select Appointment</option>
                                    @foreach($appointments as $appointment)
                                        <option value="{{ $appointment->appointment_id }}" 
                                            data-patient-id="{{ $appointment->patient_id }}"
                                            {{ $selectedAppointmentId == $appointment->appointment_id ? 'selected' : '' }}>
                                            {{ $appointment->appointment_date->format('M d, Y g:i A') }} - 
                                            Dr. {{ $appointment->dentist->first_name }} {{ $appointment->dentist->last_name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                        <option value="{{ $dentist->employee_id }}">{{ $dentist->first_name }} {{ $dentist->last_name }}</option>
                                    @endforeach
                                </select>
                                @error('dentist_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="service_id" class="form-label">Dental Service</label>
                                <select class="form-select" id="service_id" name="service_id">
                                    <option value="">Custom Treatment</option>
                                    @foreach($dentalServices as $service)
                                        <option value="{{ $service->service_id }}">
                                            {{ $service->name }} - ${{ number_format($service->standard_cost, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select a predefined service or leave as "Custom Treatment" to enter details manually</div>
                            </div>
                            <div class="col-md-6">
                                <label for="tooth_number" class="form-label">Tooth Number (1-32, if applicable)</label>
                                <input type="number" class="form-control @error('tooth_number') is-invalid @enderror" id="tooth_number" name="tooth_number" min="1" max="32">
                                @error('tooth_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Treatment Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="cost" class="form-label">Cost ($)</label>
                                <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" required>
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="duration" class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Planned">Planned</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notes" class="form-label">Clinical Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Treatment</button>
                            </div>
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
                // Clear fields if "Custom Treatment" is selected
                treatmentNameInput.value = '';
                descriptionTextarea.value = '';
                costInput.value = '';
                durationInput.value = '';
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
                    // Populate form fields with service data
                    treatmentNameInput.value = data.name;
                    descriptionTextarea.value = data.description;
                    costInput.value = data.cost;
                    durationInput.value = data.duration;
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