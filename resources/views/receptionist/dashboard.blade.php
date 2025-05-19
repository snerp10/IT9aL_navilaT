@extends('layouts.receptionist')

@section('title', 'Receptionist Dashboard')

@section('content')
<div class="row">
    <!-- Quick Stats Cards -->
    <div class="row">
        <!-- Today's Appointments Quick Stats -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Today's Appointments</h6>
                            <h3 class="mt-2">{{ $todayAppointments->count() }}</h3>
                        </div>
                        <div class="fs-1 text-primary">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Dentists -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Active Dentists</h6>
                            <h3 class="mt-2">{{ $dentists->count() }}</h3>
                        </div>
                        <div class="fs-1 text-success">
                            <i class="fas fa-user-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Upcoming (7 days)</h6>
                            <h3 class="mt-2">{{ $upcomingAppointments->count() }}</h3>
                        </div>
                        <div class="fs-1 text-info">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Recent Payments</h6>
                            <h3 class="mt-2">{{ $recentPayments->count() }}</h3>
                        </div>
                        <div class="fs-1 text-warning">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Today's Appointments -->
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Today's Appointments</h5>
                    <a href="{{ route('receptionist.appointments.index', ['view' => 'today']) }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Dentist</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                                <p class="text-muted small mb-0">{{ $appointment->patient->contact_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Dr. {{ $appointment->dentist->last_name }}</td>
                                    <td>
                                        @if($appointment->status == 'Scheduled')
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('receptionist.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($appointment->status == 'Scheduled')
                                            <form action="{{ route('receptionist.appointments.check-in', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success ms-1" title="Check In">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No appointments scheduled for today</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Patients</h5>
                    <a href="{{ route('receptionist.patients.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($recentPatients as $patient)
                        <a href="{{ route('receptionist.patients.show', $patient) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                <small>{{ $patient->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small">{{ $patient->contact_number }}</p>
                            <small class="text-muted">{{ $patient->email }}</small>
                        </a>
                        @empty
                        <div class="list-group-item">No recent patients</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('receptionist.patient-check-in') }}" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-clipboard-check me-2"></i> Patient Check-In
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-success w-100 py-3">
                                <i class="fas fa-calendar-plus me-2"></i> New Appointment
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('receptionist.patients.create') }}" class="btn btn-info w-100 py-3 text-white">
                                <i class="fas fa-user-plus me-2"></i> New Patient
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('receptionist.payments') }}" class="btn btn-warning w-100 py-3 text-dark">
                                <i class="fas fa-money-bill me-2"></i> Process Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection