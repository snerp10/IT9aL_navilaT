@extends('layouts.admin')

@section('title', 'Upcoming Appointments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Upcoming Appointments</h1>
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
                            <form method="GET" action="{{ route('appointments.index', ['status' => 'upcoming']) }}" class="row g-2">
                                <input type="hidden" name="status" value="upcoming">
                                <div class="col-md-3">
                                    <select name="period" class="form-select">
                                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="tomorrow" {{ request('period') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                                        <option value="week" {{ request('period', 'week') == 'week' ? 'selected' : '' }}>This Week</option>
                                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                                    </select>
                                </div>
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
                                    <select name="confirmation" class="form-select">
                                        <option value="">Any Status</option>
                                        <option value="confirmed" {{ request('confirmation') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="unconfirmed" {{ request('confirmation') == 'unconfirmed' ? 'selected' : '' }}>Needs Confirmation</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list"></i> All Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Today</h6>
                            <h3 class="mb-0">{{ $todayCount }}</h3>
                        </div>
                        <div class="fs-1 text-primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Confirmed</h6>
                            <h3 class="mb-0">{{ $confirmedCount }}</h3>
                        </div>
                        <div class="fs-1 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Needs Confirmation</h6>
                            <h3 class="mb-0">{{ $unconfirmedCount }}</h3>
                        </div>
                        <div class="fs-1 text-warning">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">This Week</h6>
                            <h3 class="mb-0">{{ $weekCount }}</h3>
                        </div>
                        <div class="fs-1 text-info">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Headers and Appointments -->
    <div class="card">
        <div class="card-body p-0">
            @forelse($appointmentsByDate as $date => $appointmentsForDate)
                <div class="date-header px-3 py-2 bg-light border-top border-bottom">
                    <h5 class="mb-0">{{ date('l, F j, Y', strtotime($date)) }}
                    @if($date == date('Y-m-d'))
                        <span class="badge bg-primary ms-2">Today</span>
                    @elseif($date == date('Y-m-d', strtotime('+1 day')))
                        <span class="badge bg-secondary ms-2">Tomorrow</span>
                    @endif
                    </h5>
                </div>
                
                <div class="appointments-list">
                    @foreach($appointmentsForDate as $appointment)
                        <div class="appointment-item p-3 border-bottom {{ $appointment->status === 'Cancelled' ? 'bg-light text-muted' : '' }}">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <strong>{{ date('g:i A', strtotime($appointment->appointment_date)) }}</strong>
                                    <div class="small text-muted">{{ $appointment->duration_minutes ?? 30 }} mins</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <span>{{ substr($appointment->patient->first_name ?? 'P', 0, 1) }}{{ substr($appointment->patient->last_name ?? 'P', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $appointment->patient->first_name ?? 'Unknown' }} {{ $appointment->patient->last_name ?? 'Patient' }}</div>
                                            <div class="small text-muted">{{ $appointment->patient->contact_number ?? 'No phone' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <span>{{ substr($appointment->dentist->first_name ?? 'D', 0, 1) }}{{ substr($appointment->dentist->last_name ?? 'D', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Dr. {{ $appointment->dentist->first_name ?? 'Unknown' }} {{ $appointment->dentist->last_name ?? '' }}</div>
                                            <div class="small text-muted">{{ $appointment->dentist->specialization ?? 'Dentist' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-{{ 
                                        $appointment->status === 'Scheduled' ? 'primary' : 
                                        ($appointment->status === 'Confirmed' ? 'success' : 
                                        ($appointment->status === 'Completed' ? 'info' : 
                                        ($appointment->status === 'Cancelled' ? 'danger' : 'secondary'))) 
                                    }}">{{ $appointment->status }}</span>
                                    <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($appointment->reason_for_visit, 30) }}</div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('appointments.edit', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($appointment->status !== 'Scheduled' && $appointment->status !== 'Cancelled')
                                        <!-- Simple direct form that doesn't use JavaScript or modals -->
                                        <form method="POST" action="{{ url('/appointments/' . $appointment->appointment_id) }}" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="patient_id" value="{{ $appointment->patient->patient_id ?? '' }}">
                                            <input type="hidden" name="dentist_id" value="{{ $appointment->dentist->employee_id ?? '' }}">
                                            <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                            <input type="hidden" name="reason_for_visit" value="{{ $appointment->reason_for_visit }}">
                                            <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                            <!-- Fix: Use a valid status value from the database enum -->
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to confirm this appointment?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                    </div>
                    <h4>No Upcoming Appointments Found</h4>
                    <p class="text-muted">No appointments match your current filter criteria.</p>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Schedule New Appointment
                    </a>
                </div>
            @endforelse
        </div>
        
        @if(isset($appointments))
            <div class="card-footer bg-white">
                <!-- Fix: Check if appointments is a LengthAwarePaginator instance that has the appends method -->
                @if($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $appointments->appends(request()->except('page'))->links() }}
                @endif
            </div>
        @endif
    </div>
</div>

@endsection