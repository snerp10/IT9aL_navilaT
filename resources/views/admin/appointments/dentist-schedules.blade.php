@extends('layouts.admin')

@section('title', 'Dentist Schedules')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dentist Schedules</h1>
        <div>
            <a href="{{ route('appointments.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New Appointment
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <form method="GET" action="{{ route('appointments.index', ['view' => 'dentist']) }}" class="row g-2">
                                <input type="hidden" name="view" value="dentist">
                                <div class="col-md-3">
                                    <select name="dentist_id" class="form-select">
                                        <option value="">All Dentists</option>
                                        @foreach($dentists as $dentist)
                                        <option value="{{ $dentist->employee_id }}" {{ request('dentist_id') == $dentist->employee_id ? 'selected' : '' }}>
                                            Dr. {{ $dentist->first_name }} {{ $dentist->last_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="date" value="{{ request('date', date('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="No Show" {{ request('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="btn-group">
                                <a href="{{ route('appointments.index', ['view' => 'dentist', 'date' => date('Y-m-d', strtotime('-1 day', strtotime(request('date', date('Y-m-d')))))]) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                                <button class="btn btn-outline-secondary" disabled>
                                    {{ request('date') ? date('l, M d, Y', strtotime(request('date'))) : date('l, M d, Y') }}
                                </button>
                                <a href="{{ route('appointments.index', ['view' => 'dentist', 'date' => date('Y-m-d', strtotime('+1 day', strtotime(request('date', date('Y-m-d')))))]) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($dentists as $dentist)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span>{{ substr($dentist->first_name, 0, 1) }}{{ substr($dentist->last_name, 0, 1) }}</span>
                                </div>
                                Dr. {{ $dentist->first_name }} {{ $dentist->last_name }}
                                @if($dentist->specialization)
                                <small class="text-muted ms-2">({{ $dentist->specialization }})</small>
                                @endif
                            </div>
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $dentist->appointments->count() }} appointments
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @if($dentist->appointments->count() > 0)
                            <div class="time-slots">
                                @foreach($dentist->appointments->sortBy('appointment_date') as $appointment)
                                    <div class="appointment-slot p-3 border-bottom {{ $appointment->status === 'Cancelled' ? 'bg-light text-muted' : '' }}">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $appointment->appointment_date->format('g:i A') }}</strong>
                                                <span class="badge bg-{{ 
                                                    $appointment->status === 'Scheduled' ? 'primary' : 
                                                    ($appointment->status === 'Confirmed' ? 'success' : 
                                                    ($appointment->status === 'Completed' ? 'info' : 
                                                    ($appointment->status === 'Cancelled' ? 'danger' : 'secondary'))) 
                                                }} ms-2">{{ $appointment->status }}</span>
                                            </div>
                                            <div>
                                                <a href="{{ route('appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-info me-1">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <span>{{ substr($appointment->patient->first_name ?? 'P', 0, 1) }}{{ substr($appointment->patient->last_name ?? 'P', 0, 1) }}</span>
                                                </div>
                                                {{ $appointment->patient->first_name ?? 'Unknown' }} {{ $appointment->patient->last_name ?? 'Patient' }}
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <i class="bi bi-chat-left-text me-1"></i> {{ $appointment->reason_for_visit }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                <p>No appointments scheduled for this dentist today.</p>
                                <a href="{{ route('appointments.create', ['dentist_id' => $dentist->employee_id]) }}" class="btn btn-sm btn-outline-primary">
                                    Schedule New Appointment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No dentists found in the system. Please add dentists to schedule appointments.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection