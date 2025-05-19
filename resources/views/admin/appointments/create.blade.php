@extends('layouts.admin')

@section('title', 'Add Appointment')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Add New Appointment</h1>
    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="patient_id" class="form-label">Patient</label>
                <select class="form-select" id="patient_id" name="patient_id" required>
                    <option value="">Select Patient</option>
                    @foreach ($patients as $patient)
                    <option value="{{ $patient->patient_id }}">
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
                    <option value="{{ $dentist->employee_id }}">
                        Dr. {{ $dentist->first_name }} {{ $dentist->last_name }} ({{ $dentist->specialization ?? 'General Dentist' }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="appointment_date" class="form-label">Appointment Date & Time</label>
                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Scheduled" selected>Scheduled</option>
                    <option value="Completed">Completed</option>
                    <option value="Canceled">Canceled</option>
                    <option value="No Show">No Show</option>
                </select>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="reason_for_visit" class="form-label">Reason for Visit</label>
            <textarea class="form-control" id="reason_for_visit" name="reason_for_visit" rows="3" required></textarea>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-calendar-plus"></i> Create Appointment
            </button>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
