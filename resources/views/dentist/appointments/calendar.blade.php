@extends('layouts.dentist')

@section('title', 'Appointments Calendar')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dentist.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentist.appointments.index') }}">Appointments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Calendar</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Appointments Calendar</h1>
                <div>
                    <a href="{{ route('dentist.appointments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> New Appointment
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div id="dentist-calendar"></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-dark">Legend</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-info" style="width:20px; height:20px;"></div>
                        <span class="ms-2">Scheduled</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-primary" style="width:20px; height:20px;"></div>
                        <span class="ms-2">In Progress</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-success" style="width:20px; height:20px;"></div>
                        <span class="ms-2">Completed</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="badge bg-secondary" style="width:20px; height:20px;"></div>
                        <span class="ms-2">Canceled</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="badge bg-danger" style="width:20px; height:20px;"></div>
                        <span class="ms-2">No Show</span>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-dark">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;">
                            <i class="bi bi-calendar-day text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Today's Appointments</div>
                            <div class="fs-5 fw-bold">{{ $appointments->where('appointment_date', '>=', \Carbon\Carbon::today())->where('appointment_date', '<', \Carbon\Carbon::tomorrow())->count() }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;">
                            <i class="bi bi-calendar-week text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted">This Week</div>
                            <div class="fs-5 fw-bold">{{ $appointments->where('appointment_date', '>=', \Carbon\Carbon::now()->startOfWeek())->where('appointment_date', '<=', \Carbon\Carbon::now()->endOfWeek())->count() }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;">
                            <i class="bi bi-calendar-month text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted">This Month</div>
                            <div class="fs-5 fw-bold">{{ $appointments->where('appointment_date', '>=', \Carbon\Carbon::now()->startOfMonth())->where('appointment_date', '<=', \Carbon\Carbon::now()->endOfMonth())->count() }}</div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <a href="{{ route('dentist.appointments.index') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-list-ul me-1"></i> List View
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentDetails">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px;">
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>
                        <div>
                            <h5 id="patientName" class="mb-0"></h5>
                            <p id="appointmentTime" class="text-muted mb-0"></p>
                        </div>
                        <div class="ms-auto">
                            <span id="statusBadge" class="badge rounded-pill"></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Visit</label>
                        <p id="reasonText" class="mb-0"></p>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Appointment ID</label>
                            <p id="appointmentId" class="mb-0"></p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Duration</label>
                            <p id="durationText" class="mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="viewAppointmentBtn" href="#" class="btn btn-primary">View Details</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
    #dentist-calendar {
        height: 700px;
    }
    
    .fc-event {
        cursor: pointer;
    }
    
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
    
    .fc-toolbar-title {
        font-size: 1.5rem !important;
    }
    
    .fc-button-primary {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
    }
    
    .fc-button-primary:hover {
        background-color: #0b5ed7 !important;
        border-color: #0a58ca !important;
    }
    
    .fc-event-time {
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('dentist-calendar');
    
    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap5',
        events: '{{ route('dentist.appointments.calendar-events') }}',
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        dayMaxEvents: true,
        eventClick: function(info) {
            // Show appointment details in modal
            const appointmentId = info.event.id;
            const appointmentUrl = '{{ url('/dentist/appointments/') }}/' + appointmentId;
            
            // Get appointment details via AJAX
            fetch('{{ url('/dentist/appointments/') }}/' + appointmentId + '/details')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const appointment = data.appointment;
                        const patient = appointment.patient;
                        
                        // Format date and time
                        const appointmentDate = new Date(appointment.appointment_date);
                        const formattedDate = appointmentDate.toLocaleDateString('en-US', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                        const formattedTime = appointmentDate.toLocaleTimeString('en-US', { 
                            hour: 'numeric', 
                            minute: '2-digit'
                        });
                        
                        // Update modal content
                        document.getElementById('patientName').textContent = patient ? `${patient.first_name} ${patient.last_name}` : 'Unknown Patient';
                        document.getElementById('appointmentTime').textContent = `${formattedDate} at ${formattedTime}`;
                        document.getElementById('reasonText').textContent = appointment.reason_for_visit;
                        document.getElementById('appointmentId').textContent = appointment.appointment_id;
                        document.getElementById('durationText').textContent = `${appointment.duration || 30} minutes`;
                        
                        // Set status badge
                        const statusBadge = document.getElementById('statusBadge');
                        statusBadge.textContent = appointment.status;
                        
                        switch(appointment.status) {
                            case 'Scheduled':
                                statusBadge.className = 'badge rounded-pill bg-info';
                                break;
                            case 'In Progress':
                                statusBadge.className = 'badge rounded-pill bg-primary';
                                break;
                            case 'Completed':
                                statusBadge.className = 'badge rounded-pill bg-success';
                                break;
                            case 'Canceled':
                                statusBadge.className = 'badge rounded-pill bg-secondary';
                                break;
                            case 'No Show':
                                statusBadge.className = 'badge rounded-pill bg-danger';
                                break;
                            default:
                                statusBadge.className = 'badge rounded-pill bg-info';
                        }
                        
                        // Set view details link
                        document.getElementById('viewAppointmentBtn').href = appointmentUrl;
                        
                        // Show modal
                        const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
                        appointmentModal.show();
                    }
                })
                .catch(error => console.error('Error fetching appointment details:', error));
        }
    });
    
    calendar.render();
});
</script>
@endpush