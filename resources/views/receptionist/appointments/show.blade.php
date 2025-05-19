@extends('layouts.receptionist')

@section('title', 'Appointment Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Appointment Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('receptionist.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('receptionist.appointments.index') }}">Appointments</a></li>
        <li class="breadcrumb-item active">Appointment Details</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar-check me-1"></i>
                Appointment #{{ $appointment->appointment_id }}
            </div>
            <div>
                <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if($appointment->status == 'Scheduled')
                    <form action="{{ route('receptionist.appointments.check-in', $appointment) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to check in this patient?')">
                            <i class="fas fa-check-circle"></i> Check In
                        </button>
                    </form>
                    <form action="{{ route('receptionist.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                            <i class="fas fa-times-circle"></i> Cancel
                        </button>
                    </form>
                @endif
                <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Appointment Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Date & Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($appointment->status == 'Scheduled')
                                            <span class="badge bg-primary">{{ $appointment->status }}</span>
                                        @elseif($appointment->status == 'In Progress')
                                            <span class="badge bg-info">{{ $appointment->status }}</span>
                                        @elseif($appointment->status == 'Completed')
                                            <span class="badge bg-success">{{ $appointment->status }}</span>
                                        @elseif($appointment->status == 'Canceled')
                                            <span class="badge bg-danger">{{ $appointment->status }}</span>
                                        @elseif($appointment->status == 'No Show')
                                            <span class="badge bg-warning text-dark">{{ $appointment->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reason for Visit:</th>
                                    <td>{{ $appointment->reason_for_visit }}</td>
                                </tr>
                                @if($appointment->check_in_time)
                                <tr>
                                    <th>Check-in Time:</th>
                                    <td>{{ \Carbon\Carbon::parse($appointment->check_in_time)->format('F d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Created On:</th>
                                    <td>{{ $appointment->created_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                @if($appointment->notes)
                                <tr>
                                    <th>Notes:</th>
                                    <td>{{ $appointment->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Patient Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-xl rounded-circle bg-light">
                                        <i class="fas fa-user fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">
                                        <a href="{{ route('receptionist.patients.show', $appointment->patient) }}">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-0">Patient ID: {{ $appointment->patient->patient_id }}</p>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Phone:</th>
                                    <td>{{ $appointment->patient->contact_number }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $appointment->patient->email }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td>{{ $appointment->patient->birth_date ? \Carbon\Carbon::parse($appointment->patient->birth_date)->format('F d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>{{ $appointment->patient->gender }}</td>
                                </tr>
                            </table>
                            
                            <div class="mt-3">
                                <a href="{{ route('receptionist.patients.show', $appointment->patient) }}" class="btn btn-sm btn-outline-primary">View Patient Profile</a>
                                <a href="{{ route('receptionist.appointments.patient-history', $appointment->patient) }}" class="btn btn-sm btn-outline-info">View Appointment History</a>
                                <a href="{{ route('receptionist.billing.patient', $appointment->patient) }}" class="btn btn-sm btn-outline-success">View Billing Records</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Dentist Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-xl rounded-circle bg-light">
                                        <i class="fas fa-user-md fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Dr. {{ $appointment->dentist->first_name }} {{ $appointment->dentist->last_name }}</h5>
                                    <p class="text-muted mb-0">Employee ID: {{ $appointment->dentist->employee_id }}</p>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Specialization:</th>
                                    <td>{{ $appointment->dentist->specialization ?? 'General Dentistry' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $appointment->dentist->contact_number }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $appointment->dentist->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($appointment->status == 'Completed' || $appointment->status == 'In Progress')
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Treatment Records</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $treatments = \App\Models\Treatment::where('appointment_id', $appointment->appointment_id)->get();
                            @endphp
                            
                            @if($treatments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Treatment ID</th>
                                                <th>Treatment</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($treatments as $treatment)
                                                <tr>
                                                    <td>{{ $treatment->treatment_id }}</td>
                                                    <td>{{ $treatment->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($treatment->treatment_date)->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $treatment->status == 'Completed' ? 'success' : 'primary' }}">
                                                            {{ $treatment->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-outline-info">View</a>
                                                        
                                                        @if(!$treatment->billings()->where('payment_status', 'Paid')->exists())
                                                            <a href="{{ route('receptionist.billing.create', ['treatment_id' => $treatment->treatment_id]) }}" class="btn btn-sm btn-outline-success">Create Invoice</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No treatment records found for this appointment.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            @if($appointment->status == 'Scheduled')
                                <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i> Edit Appointment
                                </a>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection