@extends('layouts.dentist')

@section('title', 'Patient Appointments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Appointment History</h1>
        <div>
            <a href="{{ route('dentist.patients.show', $patient) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Patient
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Patient Info Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fs-3">{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                            <p class="text-muted mb-0">
                                {{ $patient->gender }}, {{ \Carbon\Carbon::parse($patient->birth_date)->age }} years
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-telephone me-2"></i>Contact Number</p>
                        <p class="mb-0">{{ $patient->contact_number }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-envelope me-2"></i>Email</p>
                        <p class="mb-0">{{ $patient->email }}</p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('dentist.patients.dental-chart', $patient) }}" class="btn btn-outline-primary">
                            <i class="bi bi-grid-3x3"></i> View Dental Chart
                        </a>
                        <a href="{{ route('dentist.patients.treatments', $patient) }}" class="btn btn-outline-info">
                            <i class="bi bi-clipboard2-pulse"></i> Treatment History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Appointment Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-2">Total Appointments</h6>
                                    <h2 class="mb-0">{{ $appointments->total() }}</h2>
                                </div>
                                <i class="bi bi-calendar-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-2">Completed</h6>
                                    <h2 class="mb-0">{{ $appointments->where('status', 'Completed')->count() }}</h2>
                                </div>
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-2">Upcoming</h6>
                                    @php
                                        $upcomingCount = $patient->appointments()
                                            ->where('dentist_id', Auth::user()->employee->employee_id)
                                            ->whereIn('status', ['Scheduled', 'Confirmed'])
                                            ->where('appointment_date', '>', now())
                                            ->count();
                                    @endphp
                                    <h2 class="mb-0">{{ $upcomingCount }}</h2>
                                </div>
                                <i class="bi bi-calendar-plus fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments List -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Appointment List</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Treatments</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->appointment_date->format('M d, Y g:i A') }}</td>
                                            <td>{{ Str::limit($appointment->reason_for_visit, 50) }}</td>
                                            <td>
                                                @if($appointment->status == 'Scheduled')
                                                    <span class="badge bg-primary">{{ $appointment->status }}</span>
                                                @elseif($appointment->status == 'In Progress')
                                                    <span class="badge bg-warning">{{ $appointment->status }}</span>
                                                @elseif($appointment->status == 'Completed')
                                                    <span class="badge bg-success">{{ $appointment->status }}</span>
                                                @elseif($appointment->status == 'Canceled')
                                                    <span class="badge bg-danger">{{ $appointment->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $treatmentsCount = $appointment->treatments->count();
                                                @endphp
                                                @if($treatmentsCount > 0)
                                                    <span class="badge bg-info">{{ $treatmentsCount }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('dentist.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <p class="mt-3">No appointments have been recorded for this patient.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection