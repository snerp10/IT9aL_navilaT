@extends('layouts.admin')

@section('title', 'Appointment Calendar')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Appointment Calendar</h1>
        <div>
            <a href="{{ route('appointments.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New Appointment
            </a>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-list"></i> List View
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="btn-group">
                        <button type="button" id="prev-month" class="btn btn-outline-primary">
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <button type="button" id="today" class="btn btn-outline-secondary">Today</button>
                        <button type="button" id="next-month" class="btn btn-outline-primary">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <h5 class="current-month-display mb-0 fw-bold"></h5>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <button type="button" id="month-view" class="btn btn-outline-secondary active">Month</button>
                        <button type="button" id="week-view" class="btn btn-outline-secondary">Week</button>
                        <button type="button" id="day-view" class="btn btn-outline-secondary">Day</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
            
            <div class="mt-4">
                <h5 class="border-bottom pb-2">Status Legend</h5>
                <div class="d-flex flex-wrap gap-3 mt-3">
                    <div class="d-flex align-items-center">
                        <div class="calendar-dot bg-primary me-2"></div>
                        <span>Scheduled</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="calendar-dot bg-success me-2"></div>
                        <span>Confirmed</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="calendar-dot bg-warning me-2"></div>
                        <span>Pending</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="calendar-dot bg-danger me-2"></div>
                        <span>Cancelled</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="calendar-dot bg-info me-2"></div>
                        <span>Completed</span>
                    </div>
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
                <div class="mb-3">
                    <label class="form-label fw-bold">Patient</label>
                    <p id="modal-patient-name">Loading...</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Dentist</label>
                    <p id="modal-dentist-name">Loading...</p>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Date</label>
                        <p id="modal-date">Loading...</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Time</label>
                        <p id="modal-time">Loading...</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <p id="modal-status">Loading...</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Reason for Visit</label>
                    <p id="modal-reason">Loading...</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Notes</label>
                    <p id="modal-notes">Loading...</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="view-appointment" class="btn btn-info">View Details</a>
                <a href="#" id="edit-appointment" class="btn btn-warning">Edit</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<style>
    #calendar {
        height: 650px;
    }
    .calendar-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .fc-event {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: false,
            events: '{{ route("appointments.calendar-events") }}',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            eventClick: function(info) {
                // Get appointment details - fixed URL to use the correct path
                fetch(`/appointments/${info.event.id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Populate modal with appointment details
                        document.getElementById('modal-patient-name').textContent = 
                            data.patient ? `${data.patient.first_name} ${data.patient.last_name}` : 'Unknown Patient';
                        document.getElementById('modal-dentist-name').textContent = 
                            data.dentist ? `Dr. ${data.dentist.first_name} ${data.dentist.last_name}` : 'Unknown Dentist';
                        
                        // Format date and time
                        const apptDate = new Date(data.appointment_date);
                        document.getElementById('modal-date').textContent = apptDate.toLocaleDateString();
                        document.getElementById('modal-time').textContent = apptDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        document.getElementById('modal-status').textContent = data.status;
                        document.getElementById('modal-reason').textContent = data.reason_for_visit;
                        document.getElementById('modal-notes').textContent = data.notes || 'No notes available';
                        
                        // Update links
                        document.getElementById('view-appointment').href = `/appointments/${data.appointment_id}`;
                        document.getElementById('edit-appointment').href = `/appointments/${data.appointment_id}/edit`;
                        
                        // Display modal
                        const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
                        appointmentModal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching appointment details:', error);
                        alert('Could not load appointment details. Please try again.');
                    });
            },
            dayMaxEvents: true
        });
        
        calendar.render();
        
        // Update current month display
        function updateMonthDisplay() {
            const dateStr = calendar.view.title;
            document.querySelector('.current-month-display').textContent = dateStr;
        }
        
        updateMonthDisplay();
        
        // Button handlers
        document.getElementById('prev-month').addEventListener('click', function() {
            calendar.prev();
            updateMonthDisplay();
        });
        
        document.getElementById('next-month').addEventListener('click', function() {
            calendar.next();
            updateMonthDisplay();
        });
        
        document.getElementById('today').addEventListener('click', function() {
            calendar.today();
            updateMonthDisplay();
        });
        
        document.getElementById('month-view').addEventListener('click', function() {
            calendar.changeView('dayGridMonth');
            updateMonthDisplay();
            setActiveViewButton(this);
        });
        
        document.getElementById('week-view').addEventListener('click', function() {
            calendar.changeView('timeGridWeek');
            updateMonthDisplay();
            setActiveViewButton(this);
        });
        
        document.getElementById('day-view').addEventListener('click', function() {
            calendar.changeView('timeGridDay');
            updateMonthDisplay();
            setActiveViewButton(this);
        });
        
        function setActiveViewButton(button) {
            document.querySelectorAll('#month-view, #week-view, #day-view').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        }
    });
</script>
@endpush
@endsection