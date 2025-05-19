@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Patient Information</h6>
                    <div>
                        <a href="{{ route('receptionist.patients.index') }}" class="btn btn-sm btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Patients
                        </a>
                        <a href="{{ route('receptionist.patients.edit', $patient) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Patient
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- Status Messages -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('receptionist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-success btn-block w-100">
                                                <i class="fas fa-calendar-plus me-2"></i> New Appointment
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('receptionist.appointments.patient-history', $patient) }}" class="btn btn-info btn-block w-100">
                                                <i class="fas fa-history me-2"></i> Appointment History
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('receptionist.billing.patient', $patient) }}" class="btn btn-warning btn-block w-100">
                                                <i class="fas fa-file-invoice me-2"></i> Billing Records
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button type="button" class="btn btn-primary btn-block w-100" data-bs-toggle="modal" data-bs-target="#medicalRecordModal">
                                                <i class="fas fa-notes-medical me-2"></i> View Medical Record
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Patient ID:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->patient_id }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Full Name:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->first_name }} {{ $patient->middle_name ? $patient->middle_name . ' ' : '' }}{{ $patient->last_name }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Gender:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ ucfirst($patient->gender) }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Age:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $age }} years (DOB: {{ \Carbon\Carbon::parse($patient->birth_date)->format('M d, Y') }})
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Address:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->address }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            @if($patient->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Phone:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->contact_number }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Email:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->email }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Emergency Contact:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->emergency_contact_name }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Emergency Phone:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->emergency_contact_number }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Insurance Provider:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->insurance_provider ?: 'None' }}
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-4 text-md-end">
                                            <strong>Policy Number:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $patient->insurance_policy_number ?: 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Appointments Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Upcoming Appointments</h6>
                                    <a href="{{ route('receptionist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> New Appointment
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dentist</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Purpose</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $upcomingAppointments = $appointments->where('appointment_date', '>=', \Carbon\Carbon::today())
                                                        ->where('status', '!=', 'Canceled')
                                                        ->take(5);
                                                @endphp
                                                
                                                @forelse($upcomingAppointments as $appointment)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">Dr. {{ $appointment->dentist->last_name }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ \Illuminate\Support\Str::limit($appointment->reason_for_visit, 30) }}</p>
                                                    </td>
                                                    <td>
                                                        @if($appointment->status == 'Scheduled')
                                                            <span class="badge badge-sm bg-warning">{{ $appointment->status }}</span>
                                                        @elseif($appointment->status == 'Completed')
                                                            <span class="badge badge-sm bg-success">{{ $appointment->status }}</span>
                                                        @elseif($appointment->status == 'Canceled')
                                                            <span class="badge badge-sm bg-danger">{{ $appointment->status }}</span>
                                                        @else
                                                            <span class="badge badge-sm bg-info">{{ $appointment->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('receptionist.appointments.show', $appointment) }}" class="btn btn-link text-info text-gradient p-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-link text-warning text-gradient p-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">No upcoming appointments</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($appointments->count() > 5)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('receptionist.appointments.patient-history', $patient) }}" class="btn btn-sm btn-outline-primary">View All Appointments</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medical Record Modal -->
<div class="modal fade" id="medicalRecordModal" tabindex="-1" aria-labelledby="medicalRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicalRecordModalLabel">Medical Record - {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Blood Type:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $patient->blood_type ?: 'Not recorded' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Allergies:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $patient->allergies ?: 'None reported' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Medical History:</strong>
                    </div>
                    <div class="col-md-8">
                        {!! nl2br(e($patient->medical_history ?: 'No medical history recorded')) !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Current Medications:</strong>
                    </div>
                    <div class="col-md-8">
                        {!! nl2br(e($patient->current_medications ?: 'No medications recorded')) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('receptionist.patients.edit', $patient) }}#medical-info" class="btn btn-primary">Update Medical Information</a>
            </div>
        </div>
    </div>
</div>
@endsection