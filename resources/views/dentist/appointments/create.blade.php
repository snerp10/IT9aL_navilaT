@extends('layouts.dentist')

@section('title', 'Create New Appointment')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Appointment</h1>
        <a href="{{ route('dentist.appointments.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back to Appointments
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('dentist.appointments.store') }}">
                        @csrf
                        
                        <!-- Patient Selection -->
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->patient_id }}" {{ (old('patient_id') == $patient->patient_id || (isset($selectedPatient) && $selectedPatient->patient_id == $patient->patient_id)) ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Appointment Date and Time -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="appointment_date" id="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', now()->format('Y-m-d')) }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Time <span class="text-danger">*</span></label>
                                    <select name="appointment_time" id="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                        <option value="">Select Time</option>
                                        @foreach($suggestedTimes as $time)
                                            <option value="{{ $time }}" {{ old('appointment_time') == $time ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('h:i A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reason for Visit -->
                        <div class="mb-3">
                            <label for="reason_for_visit" class="form-label">Reason for Visit <span class="text-danger">*</span></label>
                            <textarea name="reason_for_visit" id="reason_for_visit" class="form-control @error('reason_for_visit') is-invalid @enderror" rows="3" required>{{ old('reason_for_visit') }}</textarea>
                            @error('reason_for_visit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dentist.appointments.index') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any custom JS for the appointment creation form here
});
</script>
@endpush