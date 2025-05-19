@extends('layouts.admin')

@section('title', 'Edit Appointment')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Appointment</h1>
    <form method="POST" action="{{ route('appointments.update', $appointment->appointment_id) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="patient_id" class="form-label">Patient</label>
                <select class="form-select" id="patient_id" name="patient_id" required>
                    <option value="">Select Patient</option>
                    @foreach ($patients as $patient)
                    <option value="{{ $patient->patient_id }}" {{ $appointment->patient_id == $patient->patient_id ? 'selected' : '' }}>
                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="dentist_id" class="form-label">Dentist</label>
                <select class="form-select" id="dentist_id" name="dentist_id" required>
                    <option value="">Select Dentist</option>
                    @foreach ($dentists as $dentist)
                    <option value="{{ $dentist->employee_id }}" {{ $appointment->dentist_id == $dentist->employee_id ? 'selected' : '' }}>
                        Dr. {{ $dentist->first_name }} {{ $dentist->last_name }} ({{ $dentist->specialization ?? 'General Dentist' }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="appointment_date" class="form-label">Appointment Date & Time</label>
                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" 
                    value="{{ date('Y-m-d\TH:i', strtotime($appointment->appointment_date)) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Scheduled" {{ $appointment->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Canceled" {{ $appointment->status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="No Show" {{ $appointment->status == 'No Show' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="reason_for_visit" class="form-label">Reason for Visit</label>
            <textarea class="form-control" id="reason_for_visit" name="reason_for_visit" rows="3" required>{{ $appointment->reason_for_visit }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="2">{{ $appointment->notes }}</textarea>
        </div>
        
        <div class="d-flex justify-content-between">
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Appointment
                </button>
                <a href="{{ route('appointments.show', $appointment->appointment_id) }}" class="btn btn-info ms-2">
                    <i class="bi bi-eye"></i> View Details
                </a>
            </div>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
