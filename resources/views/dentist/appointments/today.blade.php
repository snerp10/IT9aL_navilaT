@extends('layouts.dentist')

@section('title', 'Today\'s Appointments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Today's Appointments</h1>
        <div>
            <a href="{{ route('dentist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> All Appointments
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ date('l, F d, Y') }}</h5>
                <span class="badge bg-primary">{{ $appointments->count() }} Appointments</span>
            </div>
        </div>
        <div class="card-body">
            @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date->format('g:i A') }}</td>
                            <td>
                                <a href="{{ route('dentist.patients.show', $appointment->patient) }}" class="text-decoration-none">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </a>
                            </td>
                            <td>{{ Str::limit($appointment->reason_for_visit, 30) }}</td>
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
                                <a href="{{ route('dentist.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                <p class="mt-3">No appointments scheduled for today.</p>
                <a href="{{ route('dentist.dashboard') }}" class="btn btn-outline-primary mt-2">Return to Dashboard</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection