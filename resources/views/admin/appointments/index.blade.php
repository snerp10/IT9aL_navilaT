@extends('layouts.admin')

@section('title', 'All Appointments')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">All Appointments</h1>
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="{{ route('appointments.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Appointment
            </a>
        </div>
        <div>
            <form method="GET" class="d-flex">
                <select name="status" class="form-select me-2">
                    <option value="">All Status</option>
                    <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="No Show" {{ request('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Dentist</th>
                    <th>Date & Time</th>
                    <th>Reason for Visit</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_id }}</td>
                    <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                    <td>{{ $appointment->dentist->first_name }} {{ $appointment->dentist->last_name }}</td>
                    <td>{{ $appointment->appointment_date->format('M d, Y g:i A') }}</td>
                    <td>{{ Str::limit($appointment->reason_for_visit, 30) }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $appointment->status == 'Scheduled' ? 'primary' : 
                            ($appointment->status == 'Completed' ? 'success' : 
                            ($appointment->status == 'Canceled' ? 'danger' : 'warning')) 
                        }}">
                            {{ $appointment->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('appointments.destroy', $appointment->appointment_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($appointments) && method_exists($appointments, 'links'))
        <div class="mt-3">
            {{ $appointments->links('vendor.pagination.custom-theme') }}
        </div>
    @endif
</div>
@endsection
