@extends('layouts.dentist')

@section('title', 'Dentist Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">Welcome, Dr. {{ Auth::user()->employee->last_name ?? 'Doctor' }}</h1>
                    <p class="text-muted">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div>
                    <a href="{{ route('dentist.appointments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> New Appointment
                    </a>
                </div>
            </div>
            
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Warning!</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-info-circle-fill me-2"></i>Information:</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(isset($allAppointments) && $allAppointments == 0)
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-info-circle-fill me-2"></i>Data Information:</strong> 
                No appointments found in the database for your account (Employee ID: {{ Auth::user()->employee->employee_id }}). 
                <a href="{{ route('dentist.data-access') }}" class="alert-link">Click here</a> to create sample data automatically.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Action Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('dentist.appointments.today') }}" class="text-decoration-none">
                                <div class="quick-action-card bg-white">
                                    <i class="bi bi-calendar2-check text-primary"></i>
                                    <span>Today's Schedule</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('dentist.patients.index') }}" class="text-decoration-none">
                                <div class="quick-action-card bg-white">
                                    <i class="bi bi-people text-success"></i>
                                    <span>Patient Records</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('dentist.treatments.index') }}" class="text-decoration-none">
                                <div class="quick-action-card bg-white">
                                    <i class="bi bi-clipboard2-pulse text-info"></i>
                                    <span>Treatment History</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('dentist.appointments.calendar') }}" class="text-decoration-none">
                                <div class="quick-action-card bg-white">
                                    <i class="bi bi-calendar3 text-warning"></i>
                                    <span>Calendar View</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title text-dark">Today's Appointments</h5>
                            <h3 class="card-text">{{ $todayCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2">
                    <a href="{{ route('dentist.appointments.today') }}" class="text-primary small text-decoration-none">View details <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title text-dark">Upcoming Appointments</h5>
                            <h3 class="card-text">{{ $upcomingCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2">
                    <a href="{{ route('dentist.appointments.upcoming') }}" class="text-success small text-decoration-none">View details <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title text-dark">Total Patients</h5>
                            <h3 class="card-text">{{ $patientCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2">
                    <a href="{{ route('dentist.patients.index') }}" class="text-info small text-decoration-none">View details <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="bi bi-file-medical"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="card-title text-dark">Treatments (30 days)</h5>
                            <h3 class="card-text">{{ $treatmentCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 p-2">
                    <a href="{{ route('dentist.treatments.index') }}" class="text-warning small text-decoration-none">View details <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-day text-primary me-2"></i>Today's Appointments</h5>
                    <a href="{{ route('dentist.appointments.today') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($todayAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Time</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td class="ps-3 fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</td>
                                            <td>
                                                @if($appointment->patient)
                                                    <a href="{{ route('dentist.patients.show', $appointment->patient->patient_id) }}" class="text-decoration-none text-dark">
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $appointment->service ?? 'General Checkup' }}</td>
                                            <td>
                                                <span class="badge rounded-pill bg-{{ $appointment->status == 'Completed' ? 'success' : ($appointment->status == 'Scheduled' ? 'info' : ($appointment->status == 'In Progress' ? 'primary' : 'warning')) }}">
                                                    {{ $appointment->status }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="btn-group">
                                                    <a href="{{ route('dentist.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('dentist.treatments.create', ['appointment_id' => $appointment->appointment_id]) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center p-5 text-center">
                            <div>
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">No appointments scheduled for today.</p>
                                <a href="{{ route('dentist.appointments.create') }}" class="btn btn-sm btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-1"></i> Create Appointment
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-week text-success me-2"></i>Upcoming Appointments</h5>
                    <a href="{{ route('dentist.appointments.upcoming') }}" class="btn btn-sm btn-outline-success">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($upcomingAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Date</th>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Service</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAppointments as $appointment)
                                        <tr>
                                            <td class="ps-3">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</td>
                                            <td>
                                                @if($appointment->patient)
                                                    <a href="{{ route('dentist.patients.show', $appointment->patient->patient_id) }}" class="text-decoration-none text-dark">
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $appointment->service ?? 'General Checkup' }}</td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('dentist.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center p-5 text-center">
                            <div>
                                <i class="bi bi-calendar text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">No upcoming appointments scheduled.</p>
                                <a href="{{ route('dentist.appointments.calendar') }}" class="btn btn-sm btn-success mt-3">
                                    <i class="bi bi-calendar-plus me-1"></i> View Calendar
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-people text-info me-2"></i>Recent Patients</h5>
                    <a href="{{ route('dentist.patients.index', ['recent' => 'true']) }}" class="btn btn-sm btn-outline-info">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentPatients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Patient</th>
                                        <th>Contact</th>
                                        <th>Last Visit</th>
                                        <th>Medical Notes</th>
                                        <th class="text-end pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPatients as $patient)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-light text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                        <span>{{ substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="fw-medium">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                                                        <div class="small text-muted">ID: {{ $patient->patient_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $patient->phone_number }}</div>
                                                <div class="small text-muted">{{ $patient->email }}</div>
                                            </td>
                                            <td>
                                                @if($patient->last_visit_date)
                                                    <span class="badge bg-light text-dark">{{ \Carbon\Carbon::parse($patient->last_visit_date)->format('M d, Y') }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No previous visit</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($patient->medical_notes)
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $patient->medical_notes }}">
                                                        {{ \Illuminate\Support\Str::limit($patient->medical_notes, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">No medical notes</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="btn-group">
                                                    <a href="{{ route('dentist.patients.show', $patient->patient_id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-person-lines-fill"></i>
                                                    </a>
                                                    <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-plus-circle"></i> Treatment
                                                    </a>
                                                    <a href="{{ route('dentist.patients.dental-chart', $patient->patient_id) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-grid-3x3"></i> Dental Chart
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center p-5 text-center">
                            <div>
                                <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">No recent patients found.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stats-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
    }
    
    .card-title {
        font-size: 0.85rem;
        color: #212529; /* Changed from #6c757d to a darker color */
        margin-bottom: 0.25rem;
        font-weight: 600; /* Increased from 500 to 600 */
    }
    
    .card-text {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 1.8rem;
    }
    
    .quick-action-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.2rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        height: 100%;
    }
    
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
    }
    
    .quick-action-card i {
        font-size: 1.8rem;
        margin-bottom: 0.6rem;
    }
    
    .quick-action-card span {
        font-weight: 500;
        color: #495057;
    }
    
    .avatar {
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
</style>
@endpush