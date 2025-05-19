@extends('layouts.admin')
@section('title', 'Patient Details')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Patient Details</h1>
    <div class="card mb-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_id }}</h5>
            <span class="badge bg-{{ $patient->status == 'active' ? 'success' : 'warning' }}">{{ ucfirst($patient->status) }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Personal Information</h6>
                    <p class="mb-1"><strong>Email:</strong> {{ $patient->email }}</p>
                    <p class="mb-1"><strong>Contact:</strong> {{ $patient->contact_number }}</p>
                    <p class="mb-1"><strong>Birth Date:</strong> {{ $patient->birth_date->format('M d, Y') }} ({{ $age }} years old)</p>
                    <p class="mb-1"><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $patient->address }}</p>
                    <p class="mb-1"><strong>Emergency Contact:</strong> {{ $patient->emergency_contact_name }} ({{ $patient->emergency_contact_number }})</p>
                </div>
                <div class="col-md-6">
                    <h6>Medical Information</h6>
                    <p class="mb-1"><strong>Blood Type:</strong> {{ $patient->blood_type ?: 'Not recorded' }}</p>
                    <p class="mb-1"><strong>Allergies:</strong> {{ $patient->allergies ?: 'None reported' }}</p>
                    <p class="mb-1"><strong>Medical History:</strong> {{ $patient->medical_history ?: 'None reported' }}</p>
                    <p class="mb-1"><strong>Current Medications:</strong> {{ $patient->current_medications ?: 'None reported' }}</p>
                    <p class="mb-1"><strong>Insurance:</strong> {{ $patient->insurance_provider ?: 'None' }} {{ $patient->insurance_policy_number ? "({$patient->insurance_policy_number})" : '' }}</p>
                    <p class="mb-1"><strong>Created:</strong> {{ $patient->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit Patient
                </a>
                <a href="{{ route('appointments.create') }}?patient_id={{ $patient->patient_id }}" class="btn btn-success">
                    <i class="bi bi-calendar-plus"></i> Schedule Appointment
                </a>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Appointments</h5>
                    <a href="{{ route('appointments.index') }}?patient_id={{ $patient->patient_id }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                        <h6>Upcoming Appointments</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Dentist</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAppointments as $appt)
                                        <tr>
                                            <td>{{ $appt->appointment_date->format('M d, Y g:i A') }}</td>
                                            <td>Dr. {{ $appt->dentist->first_name ?? '' }} {{ $appt->dentist->last_name ?? '' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $appt->status == 'Scheduled' ? 'primary' : ($appt->status == 'Completed' ? 'success' : 'warning') }}">
                                                    {{ $appt->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('appointments.show', $appt->appointment_id) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <h6 class="mt-4">Past Appointments</h6>
                    @if(isset($appointments) && $appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Dentist</th>
                                        <th>Treatments</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments->where('appointment_date', '<', now())->take(5) as $appt)
                                        <tr>
                                            <td>{{ $appt->appointment_date->format('M d, Y') }}</td>
                                            <td>Dr. {{ $appt->dentist->first_name ?? '' }} {{ $appt->dentist->last_name ?? '' }}</td>
                                            <td>{{ $appt->treatments->count() }}</td>
                                            <td>
                                                <span class="badge bg-{{ $appt->status == 'Completed' ? 'success' : ($appt->status == 'No Show' ? 'danger' : 'secondary') }}">
                                                    {{ $appt->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('appointments.show', $appt->appointment_id) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No past appointments found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Billing</h5>
                    <a href="{{ route('billing.patient-billings', $patient->patient_id) }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($billing) && $billing->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($billing as $bill)
                                        <tr>
                                            <td>{{ $bill->created_at->format('M d, Y') }}</td>
                                            <td>{{ $bill->invoice_number ?? 'N/A' }}</td>
                                            <td>₱{{ number_format($bill->amount_due, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $bill->payment_status == 'Paid' ? 'success' : ($bill->payment_status == 'Overdue' ? 'danger' : 'warning') }}">
                                                    {{ $bill->payment_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('billing.show', $bill->billing_id) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No billing records found.</p>
                    @endif

                    <div class="mt-4">
                        <h6>Billing Summary</h6>
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-md-4 text-center border-end">
                                        <p class="mb-0 small">Total Billed</p>
                                        <p class="fs-5 fw-bold text-primary mb-0">₱{{ number_format($billing->sum('amount_due') ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-4 text-center border-end">
                                        <p class="mb-0 small">Total Paid</p>
                                        <p class="fs-5 fw-bold text-success mb-0">₱{{ number_format($billing->sum('amount_paid') ?? 0, 2) }}</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <p class="mb-0 small">Outstanding</p>
                                        <p class="fs-5 fw-bold text-danger mb-0">₱{{ number_format(($billing->sum('amount_due') - $billing->sum('amount_paid')) ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
