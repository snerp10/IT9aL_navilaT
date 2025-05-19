@extends('layouts.receptionist')

@section('title', 'Appointments Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Appointments Management</h5>
                <div>
                    <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> New Appointment
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

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="btn-group" role="group">
                            <a href="{{ route('receptionist.appointments.index', ['view' => 'today']) }}" class="btn btn-outline-primary {{ request('view') == 'today' ? 'active' : '' }}">
                                Today
                            </a>
                            <a href="{{ route('receptionist.appointments.index', ['view' => 'week']) }}" class="btn btn-outline-primary {{ request('view') == 'week' ? 'active' : '' }}">
                                This Week
                            </a>
                            <a href="{{ route('receptionist.appointments.index', ['view' => 'dentist']) }}" class="btn btn-outline-primary {{ request('view') == 'dentist' ? 'active' : '' }}">
                                By Dentist
                            </a>
                            <a href="{{ route('receptionist.appointments.index', ['view' => 'calendar']) }}" class="btn btn-outline-primary {{ request('view') == 'calendar' ? 'active' : '' }}">
                                Calendar
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('receptionist.appointments.index') }}" method="GET" class="d-flex">
                            <select name="status" class="form-control form-control-sm me-2">
                                <option value="">All Statuses</option>
                                <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="No Show" {{ request('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        </form>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Patient</th>
                                <th>Dentist</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span><br>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                            <p class="text-muted small mb-0">{{ $appointment->patient->contact_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Dr. {{ $appointment->dentist->last_name }}</td>
                                <td>{{ Str::limit($appointment->reason_for_visit, 30) }}</td>
                                <td>
                                    @if($appointment->status == 'Scheduled')
                                        <span class="badge bg-warning">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'Completed')
                                        <span class="badge bg-success">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'Canceled')
                                        <span class="badge bg-danger">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'No Show')
                                        <span class="badge bg-danger">{{ $appointment->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('receptionist.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-primary ms-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($appointment->status == 'Scheduled')
                                            <form action="{{ route('receptionist.appointments.check-in', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success ms-1" title="Check In">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('receptionist.appointments.cancel', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1" title="Cancel">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No appointments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links('vendor.pagination.custom-theme') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection