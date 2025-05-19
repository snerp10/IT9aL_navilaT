@extends('layouts.dentist')

@section('title', 'My Patients')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Patients</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('dentist.patients.index') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('dentist.patients.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($patients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Contact</th>
                                        <th>Age</th>
                                        <th>Last Visit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <span>{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $patient->gender }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="bi bi-telephone me-1"></i> {{ $patient->contact_number }}<br>
                                                    <i class="bi bi-envelope me-1"></i> {{ $patient->email }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($patient->birth_date)->age }} years
                                            </td>
                                            <td>
                                                @php
                                                    $lastAppointment = $patient->appointments()
                                                        ->where('dentist_id', Auth::user()->employee->employee_id)
                                                        ->where('appointment_date', '<', now())
                                                        ->orderBy('appointment_date', 'desc')
                                                        ->first();
                                                @endphp
                                                
                                                @if($lastAppointment)
                                                    {{ $lastAppointment->appointment_date->format('M d, Y') }}
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $lastAppointment->appointment_date->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">No previous visits</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('dentist.patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                                        Profile
                                                    </a>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            More
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('dentist.patients.dental-chart', $patient) }}">
                                                                    <i class="bi bi-grid-3x3 me-2"></i> Dental Chart
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('dentist.patients.treatments', $patient) }}">
                                                                    <i class="bi bi-clipboard2-pulse me-2"></i> Treatments
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('dentist.patients.appointments', $patient) }}">
                                                                    <i class="bi bi-calendar-check me-2"></i> Appointments
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $patients->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <p class="mt-3">No patients found matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection