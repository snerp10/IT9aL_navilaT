@extends('layouts.receptionist')

@section('title', 'Edit Appointment')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Appointment</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('receptionist.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('receptionist.appointments.index') }}">Appointments</a></li>
        <li class="breadcrumb-item active">Edit Appointment</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Appointment #{{ $appointment->appointment_id }}
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('receptionist.appointments.update', $appointment) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Appointment Details</h5>
                            </div>
                            <div class="card-body">
                                <!-- Patient Selection -->
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient *</label>
                                    <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->patient_id }}" {{ $appointment->patient_id == $patient->patient_id ? 'selected' : '' }}>
                                                {{ $patient->last_name }}, {{ $patient->first_name }} ({{ $patient->contact_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dentist Selection -->
                                <div class="mb-3">
                                    <label for="dentist_id" class="form-label">Dentist *</label>
                                    <select name="dentist_id" id="dentist_id" class="form-select @error('dentist_id') is-invalid @enderror" required>
                                        <option value="">Select Dentist</option>
                                        @foreach($dentists as $dentist)
                                            <option value="{{ $dentist->employee_id }}" {{ $appointment->dentist_id == $dentist->employee_id ? 'selected' : '' }}>
                                                Dr. {{ $dentist->last_name }}, {{ $dentist->first_name }} 
                                                @if($dentist->specialization)
                                                    ({{ $dentist->specialization }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dentist_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Appointment Date and Time -->
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Appointment Date & Time *</label>
                                    <input type="datetime-local" name="appointment_date" id="appointment_date" 
                                        class="form-control @error('appointment_date') is-invalid @enderror" 
                                        value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i')) }}" 
                                        required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Reason for Visit -->
                                <div class="mb-3">
                                    <label for="reason_for_visit" class="form-label">Reason for Visit *</label>
                                    <input type="text" name="reason_for_visit" id="reason_for_visit" 
                                        class="form-control @error('reason_for_visit') is-invalid @enderror" 
                                        value="{{ old('reason_for_visit', $appointment->reason_for_visit) }}" 
                                        required>
                                    @error('reason_for_visit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Appointment Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="Scheduled" {{ $appointment->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Canceled" {{ $appointment->status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                        <option value="No Show" {{ $appointment->status == 'No Show' ? 'selected' : '' }}>No Show</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Service Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label">Available Dental Services</label>
                                    <div class="list-group">
                                        @foreach($dentalServices as $service)
                                            <div class="list-group-item">
                                                <h6 class="mb-1">{{ $service->name }}</h6>
                                                <p class="mb-1 text-muted small">{{ $service->description }}</p>
                                                <div class="d-flex justify-content-between">
                                                    <p class="mb-0 text-primary">Price: ₱{{ number_format($service->price, 2) }}</p>
                                                    <p class="mb-0 text-muted small">Duration: {{ $service->duration }} minutes</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="alert alert-info" role="alert">
                                    <h5 class="alert-heading">Appointment Guidelines</h5>
                                    <ul class="mb-0">
                                        <li>Appointments can be rescheduled at least 24 hours before the scheduled time.</li>
                                        <li>If the patient is more than 15 minutes late, the appointment may need to be rescheduled.</li>
                                        <li>Please add any special requests or concerns in the Notes section.</li>
                                        <li>For emergency cases, please call the clinic directly.</li>
                                    </ul>
                                </div>
                                
                                @if($appointment->check_in_time)
                                <div class="alert alert-success" role="alert">
                                    <h5 class="alert-heading">Patient Check-in Information</h5>
                                    <p class="mb-0">Patient was checked in on: <strong>{{ \Carbon\Carbon::parse($appointment->check_in_time)->format('F d, Y h:i A') }}</strong></p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('receptionist.appointments.show', $appointment) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Any JavaScript for enhancing the form can go here
    });
</script>
@endpush