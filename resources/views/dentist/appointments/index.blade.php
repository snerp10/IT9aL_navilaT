@extends('layouts.dentist')

@section('title', 'My Appointments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Appointments</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('dentist.appointments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search patients..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                        <option value="No Show" {{ request('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_from" placeholder="From Date" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_to" placeholder="To Date" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dentist.appointments.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('dentist.appointments.index') }}">All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'Scheduled' ? 'active' : '' }}" href="{{ route('dentist.appointments.index', ['status' => 'Scheduled']) }}">Scheduled</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'In Progress' ? 'active' : '' }}" href="{{ route('dentist.appointments.index', ['status' => 'In Progress']) }}">In Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'Completed' ? 'active' : '' }}" href="{{ route('dentist.appointments.index', ['status' => 'Completed']) }}">Completed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'Canceled' ? 'active' : '' }}" href="{{ route('dentist.appointments.index', ['status' => 'Canceled']) }}">Canceled</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Patient</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('M d, Y g:i A') }}</td>
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
                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                        <p class="mt-3">No appointments found matching your criteria.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection