@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="mb-4">Admin Dashboard</h1>
    <p>Welcome, Admin! Here you can manage users, view reports, oversee billing, inventory, and more.</p>
    <!-- Dashboard Widgets -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card text-bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-6">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-bg-info h-100">
                <div class="card-body">
                    <h5 class="card-title">Patients</h5>
                    <p class="card-text display-6">{{ $totalPatients }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Dentists</h5>
                    <p class="card-text display-6">{{ $totalDentists }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Receptionists</h5>
                    <p class="card-text display-6">{{ $totalReceptionists }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-bg-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    <p class="card-text display-6">{{ $totalAdmins }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-bg-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Employees</h5>
                    <p class="card-text display-6">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Management and Employee Management Widgets -->
    <div class="row mb-4">
        <!-- Patient Management Widget -->
        <div class="col-md-6 mb-3">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Patient Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="list-group mb-3">
                                <a href="{{ route('patients.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div><i class="bi bi-list-ul me-2"></i>All Patients</div>
                                    <span class="badge bg-primary rounded-pill">{{ $totalPatients }}</span>
                                </a>
                                <a href="{{ route('patients.create') }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-plus-circle me-2"></i>Add New Patient
                                </a>
                                <a href="{{ route('patients.index', ['status' => 'active']) }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-person-check me-2"></i>Active Patients
                                </a>
                                <a href="{{ route('appointments.index', ['view' => 'patient']) }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-calendar-event me-2"></i>Patient Appointments
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Patient Quick Search</h6>
                                    <form action="{{ route('patients.index') }}" method="GET">
                                        <div class="mb-2">
                                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search patients...">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-info">Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Management Widget -->
        <div class="col-md-6 mb-3">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Employee Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="list-group mb-3">
                                <a href="{{ route('users.index', ['role' => 'Dentist']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div><i class="bi bi-person-badge-fill me-2"></i>Dentists</div>
                                    <span class="badge bg-success rounded-pill">{{ $totalDentists }}</span>
                                </a>
                                <a href="{{ route('users.index', ['role' => 'Receptionist']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div><i class="bi bi-person-workspace me-2"></i>Receptionists</div>
                                    <span class="badge bg-warning rounded-pill">{{ $totalReceptionists }}</span>
                                </a>
                                <a href="{{ route('users.index', ['role' => 'Admin']) }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-person-lock me-2"></i>Admins
                                </a>
                                <a href="{{ route('payroll.index') }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-cash-coin me-2"></i>Payroll
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">Add New Employee</a>
                                        <a href="{{ route('appointments.index', ['view' => 'dentist']) }}" class="btn btn-sm btn-outline-success">Dentist Schedules</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Financial Summary</h5>
                    <p>Total Revenue: <strong class="text-success">₱{{ number_format($totalRevenue, 2) }}</strong></p>
                    <p>Outstanding Balance: <strong class="text-danger">₱{{ number_format($outstandingBalance, 2) }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Low Stock Products</h5>
                    @if($lowStockProducts->count())
                        <ul class="list-group">
                            @foreach($lowStockProducts as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $product->name }}
                                    <span class="badge bg-danger rounded-pill">{{ $product->quantity }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-success">All products sufficiently stocked.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Appointments (Next 7 Days)</h5>
                    @if($upcomingAppointments->count())
                        <ul class="list-group">
                            @foreach($upcomingAppointments as $appt)
                                <li class="list-group-item">
                                    <strong>{{ $appt->appointment_date->format('M d, Y H:i') }}</strong> -
                                    {{ $appt->patient->first_name ?? '' }} {{ $appt->patient->last_name ?? '' }} with
                                    Dr. {{ $appt->dentist->first_name ?? '' }} {{ $appt->dentist->last_name ?? '' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No upcoming appointments in the next 7 days.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Recent Users</h5>
                    @if($recentUsers->count())
                        <ul class="list-group">
                            @foreach($recentUsers as $user)
                                <li class="list-group-item">
                                    {{ $user->name }} ({{ $user->role }})<br>
                                    <small class="text-muted">Joined: {{ $user->created_at->format('M d, Y H:i') }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No recent users.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
