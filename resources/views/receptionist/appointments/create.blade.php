@extends('layouts.app')

@section('title', 'Create New Appointment')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Create New Appointment</h6>
                    <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Appointments
                    </a>
                </div>
                <div class="card-body p-3">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('receptionist.appointments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Patient Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="patient_id" class="form-label">Select Patient <span class="text-danger">*</span></label>
                                            <select class="form-control" id="patient_id" name="patient_id" required>
                                                <option value="">Select a patient</option>
                                                @foreach($patients as $patient)
                                                    <option value="{{ $patient->patient_id }}" {{ request('patient_id') == $patient->patient_id ? 'selected' : '' }}>
                                                        {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->contact_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="mt-2">
                                                <a href="{{ route('receptionist.patients.create') }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-user-plus me-1"></i> Register New Patient
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="reason_for_visit" class="form-label">Reason for Visit <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="reason_for_visit" name="reason_for_visit" rows="3" required>{{ old('reason_for_visit') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Appointment Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="dentist_id" class="form-label">Select Dentist <span class="text-danger">*</span></label>
                                            <select class="form-control" id="dentist_id" name="dentist_id" required>
                                                <option value="">Select a dentist</option>
                                                @foreach($dentists as $dentist)
                                                    <option value="{{ $dentist->employee_id }}" {{ old('dentist_id') == $dentist->employee_id ? 'selected' : '' }}>
                                                        Dr. {{ $dentist->first_name }} {{ $dentist->last_name }}
                                                        @if($dentist->specialization)
                                                         ({{ $dentist->specialization }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="appointment_date" class="form-label">Appointment Date & Time <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                                            <div class="form-text">
                                                Please select a date and time during regular office hours (8:00 AM - 5:00 PM, Monday to Saturday).
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="Canceled" {{ old('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                                <option value="No Show" {{ old('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create Appointment</button>
                            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Set default date and time if not already set
        const appointmentDateInput = document.getElementById('appointment_date');
        if (!appointmentDateInput.value) {
            const now = new Date();
            
            // Round to the nearest 30 minutes
            const minutes = Math.ceil(now.getMinutes() / 30) * 30;
            now.setMinutes(minutes);
            now.setSeconds(0);
            
            // Add one day to make it tomorrow
            now.setDate(now.getDate() + 1);
            
            // Format to datetime-local format: YYYY-MM-DDTHH:MM
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const mins = String(now.getMinutes()).padStart(2, '0');
            
            appointmentDateInput.value = `${year}-${month}-${day}T${hours}:${mins}`;
        }
        
        // Initialize any select2 or other enhanced select dropdowns if needed
        // For example:
        // $('#patient_id').select2();
        // $('#dentist_id').select2();
    });
</script>
@endpush
@endsection