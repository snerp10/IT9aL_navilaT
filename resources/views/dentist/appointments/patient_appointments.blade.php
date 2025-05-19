@extends('layouts.dentist')

@section('title', 'Patient Appointments - ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $patient->first_name }} {{ $patient->last_name }}'s Appointments</h1>
            <p class="text-muted">Patient ID: {{ $patient->patient_id }}</p>
        </div>
        <div>
            <a href="{{ route('dentist.patients.show', $patient) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-person me-1"></i> Patient Profile
            </a>
            <a href="{{ route('dentist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> New Appointment
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#all" data-bs-toggle="tab">All Appointments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#upcoming" data-bs-toggle="tab">Upcoming</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#past" data-bs-toggle="tab">Past</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all">
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
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y g:i A') }}</td>
                                                    <td>{{ $appointment->reason_for_visit }}</td>
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
                                                        <span class="badge bg-info rounded-pill">{{ $appointment->treatments->count() }}</span>
                                                        @if($appointment->treatments->count() > 0)
                                                            <small class="text-muted d-block">
                                                                {{ Str::limit($appointment->treatments->pluck('name')->join(', '), 30) }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('dentist.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                                                View
                                                            </a>
                                                            @if(in_array($appointment->status, ['Scheduled', 'In Progress']))
                                                                <a href="{{ route('dentist.treatments.create', ['appointment_id' => $appointment->appointment_id]) }}" class="btn btn-sm btn-outline-success">
                                                                    Add Treatment
                                                                </a>
                                                            @endif
                                                        </div>
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
                                    <p class="mt-3">No appointments found for this patient.</p>
                                    <a href="{{ route('dentist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Create First Appointment
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="upcoming">
                            @php
                                $upcomingAppointments = $appointments->filter(function($appointment) {
                                    return \Carbon\Carbon::parse($appointment->appointment_date)->isFuture() &&
                                          in_array($appointment->status, ['Scheduled', 'Confirmed', 'In Progress']);
                                });
                            @endphp
                            
                            @if($upcomingAppointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcomingAppointments as $appointment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y g:i A') }}</td>
                                                    <td>{{ $appointment->reason_for_visit }}</td>
                                                    <td>
                                                        @if($appointment->status == 'Scheduled')
                                                            <span class="badge bg-primary">{{ $appointment->status }}</span>
                                                        @elseif($appointment->status == 'In Progress')
                                                            <span class="badge bg-warning">{{ $appointment->status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('dentist.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                                                View
                                                            </a>
                                                            <a href="{{ route('dentist.treatments.create', ['appointment_id' => $appointment->appointment_id]) }}" class="btn btn-sm btn-outline-success">
                                                                Add Treatment
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-check fs-1 text-muted"></i>
                                    <p class="mt-3">No upcoming appointments found for this patient.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="past">
                            @php
                                $pastAppointments = $appointments->filter(function($appointment) {
                                    return \Carbon\Carbon::parse($appointment->appointment_date)->isPast() ||
                                          in_array($appointment->status, ['Completed', 'Canceled', 'No Show']);
                                });
                            @endphp
                            
                            @if($pastAppointments->count() > 0)
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
                                            @foreach($pastAppointments as $appointment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y g:i A') }}</td>
                                                    <td>{{ $appointment->reason_for_visit }}</td>
                                                    <td>
                                                        @if($appointment->status == 'Completed')
                                                            <span class="badge bg-success">{{ $appointment->status }}</span>
                                                        @elseif($appointment->status == 'Canceled')
                                                            <span class="badge bg-danger">{{ $appointment->status }}</span>
                                                        @elseif($appointment->status == 'No Show')
                                                            <span class="badge bg-dark">{{ $appointment->status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info rounded-pill">{{ $appointment->treatments->count() }}</span>
                                                        @if($appointment->treatments->count() > 0)
                                                            <small class="text-muted d-block">
                                                                {{ Str::limit($appointment->treatments->pluck('name')->join(', '), 30) }}
                                                            </small>
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
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-event fs-1 text-muted"></i>
                                    <p class="mt-3">No past appointments found for this patient.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tabs
    const triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'))
    triggerTabList.forEach(function (triggerEl) {
        new bootstrap.Tab(triggerEl)
    });
});
</script>
@endpush