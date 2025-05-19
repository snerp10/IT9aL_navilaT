@extends('layouts.admin')

@section('title', 'Appointment Details')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Appointment Details</h1>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Appointment #{{ $appointment->appointment_id }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Patient Information</h6>
                    <p class="mb-1"><strong>Name:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                    <p class="mb-1"><strong>ID:</strong> {{ $appointment->patient->patient_id }}</p>
                    <p class="mb-1"><strong>Contact:</strong> {{ $appointment->patient->contact_number }}</p>
                    <p><strong>Email:</strong> {{ $appointment->patient->email }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Dentist Information</h6>
                    <p class="mb-1"><strong>Name:</strong> Dr. {{ $appointment->dentist->first_name }} {{ $appointment->dentist->last_name }}</p>
                    <p class="mb-1"><strong>ID:</strong> {{ $appointment->dentist->employee_id }}</p>
                    <p class="mb-1"><strong>Specialization:</strong> {{ $appointment->dentist->specialization ?? 'General Dentistry' }}</p>
                </div>
            </div>
            
            <hr>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Appointment Details</h6>
                    <p class="mb-1"><strong>Date & Time:</strong> {{ $appointment->appointment_date->format('F d, Y g:i A') }}</p>
                    <p class="mb-1">
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ 
                            $appointment->status == 'Scheduled' ? 'primary' : 
                            ($appointment->status == 'Completed' ? 'success' : 
                            ($appointment->status == 'Canceled' ? 'danger' : 'warning')) 
                        }}">
                            {{ $appointment->status }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Created:</strong> {{ $appointment->created_at->format('F d, Y g:i A') }}</p>
                    <p><strong>Last Updated:</strong> {{ $appointment->updated_at->format('F d, Y g:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Visit Information</h6>
                    <p class="mb-1"><strong>Reason for Visit:</strong> {{ $appointment->reason_for_visit }}</p>
                    <p><strong>Notes:</strong> {{ $appointment->notes ?? 'No notes available' }}</p>
                </div>
            </div>
            
            @if($appointment->treatments && $appointment->treatments->count() > 0)
            <hr>
            <h6>Treatments Associated with this Appointment</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Description</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointment->treatments as $treatment)
                        <tr>
                            <td>{{ $treatment->treatment_name }}</td>
                            <td>₱{{ number_format($treatment->cost, 2) }}</td>
                            <td>{{ Str::limit($treatment->description, 50) }}</td>
                            <td>{{ $treatment->duration ?? 'N/A' }} min</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <div>
                <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit Appointment
                </a>
                @if($appointment->status == 'Scheduled')
                <form action="{{ route('appointments.destroy', $appointment->appointment_id) }}" method="POST" class="d-inline" 
                    onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger ms-2">
                        <i class="bi bi-x-circle"></i> Cancel Appointment
                    </button>
                </form>
                @endif
            </div>
            
            <div>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('patients.show', $appointment->patient_id) }}" class="btn btn-info ms-2">
                    <i class="bi bi-person"></i> View Patient
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
