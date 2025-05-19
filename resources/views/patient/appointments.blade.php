@extends('layouts.patient')

@section('title', 'My Appointments')

@section('content')
<div class="row fade-in">
    <!-- Appointment Tabs Nav -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-pills nav-fill" id="appointmentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-calendar-event me-1"></i> Upcoming
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-calendar-check me-1"></i> Past
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-calendar-x me-1"></i> Cancelled
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-calendar3 me-1"></i> Calendar View
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Appointments Content -->
    <div class="col-12">
        <div class="tab-content" id="appointmentTabsContent">
            <!-- Upcoming Appointments Tab -->
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Upcoming Appointments</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                        <i class="bi bi-plus-circle me-2"></i> Book Appointment
                    </button>
                </div>
                
                @if(isset($upcomingAppointments) && count($upcomingAppointments) > 0)
                    @foreach($upcomingAppointments as $appointment)
                        <div class="card mb-3 appointment-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 text-center border-end">
                                        <div class="p-3">
                                            <h3 class="mb-0">{{ date('d', strtotime($appointment->appointment_date ?? now()->addDays(rand(1, 30)))) }}</h3>
                                            <p class="text-muted mb-0">{{ date('M', strtotime($appointment->appointment_date ?? now()->addDays(rand(1, 30)))) }}</p>
                                            <p class="mb-0 mt-2 badge bg-primary">{{ date('g:i A', strtotime($appointment->appointment_date ?? now()->addDays(rand(1, 30)))) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3">
                                            <h5 class="mb-1">{{ $appointment->reason_for_visit }}</h5>
                                            <p class="text-muted mb-3">
                                                <i class="bi bi-person-badge me-2"></i> 
                                                Dr. {{ $appointment->dentist->user->name ?? 'Not Assigned' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                                        <div class="p-3 text-end">
                                            <span class="badge bg-{{ $appointment->status === 'Confirmed' ? 'success' : 'warning' }} mb-3">
                                                {{ $appointment->status ?? 'Confirmed' }}
                                            </span>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('patient.appointments.show', $appointment->appointment_id) }}" class="btn btn-primary">Details</a>
                                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appointment->appointment_id }}">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cancel Modal for each appointment -->
                        <div class="modal fade" id="cancelModal{{ $appointment->appointment_id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $appointment->appointment_id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancelModalLabel{{ $appointment->appointment_id }}">Cancel Appointment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('patient.appointments.cancel', $appointment->appointment_id) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Are you sure you want to cancel your appointment for <strong>{{ $appointment->reason_for_visit }}</strong> on <strong>{{ date('F j, Y', strtotime($appointment->appointment_date ?? now()->addDays(rand(1, 30)))) }}</strong> at <strong>{{ date('g:i A', strtotime($appointment->appointment_date ?? now()->addDays(rand(1, 30)))) }}</strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i> Please note that cancellations made less than 24 hours before the scheduled time may incur a cancellation fee.
                                            </div>
                                            <div class="mb-3">
                                                <label for="cancellationReason{{ $appointment->appointment_id }}" class="form-label">Reason for cancellation (optional)</label>
                                                <textarea class="form-control" id="cancellationReason{{ $appointment->appointment_id }}" name="cancellation_reason" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <i class="bi bi-calendar-plus text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No upcoming appointments</h5>
                        <p class="text-muted mb-4">You don't have any scheduled appointments</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                            <i class="bi bi-plus-circle me-2"></i> Book Your First Appointment
                        </button>
                    </div>
                @endif
            </div>
            
            <!-- Past Appointments Tab -->
            <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Past Appointments</h4>
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search past appointments">
                    </div>
                </div>
                
                @if(isset($pastAppointments) && count($pastAppointments) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Service</th>
                                    <th>Dentist</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pastAppointments as $appointment)
                                    <tr>
                                        <td>
                                            {{ date('M d, Y', strtotime($appointment->appointment_date ?? now()->subDays(rand(1, 60)))) }}
                                            <div class="text-muted small">{{ date('g:i A', strtotime($appointment->appointment_date ?? now()->subDays(rand(1, 60)))) }}</div>
                                        </td>
                                        <td>{{ $appointment->reason_for_visit }}</td>
                                        <td>Dr. {{ $appointment->dentist->user->name ?? 'Not Assigned' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($appointment->status ?? 'Completed') === 'Completed' ? 'success' : (($appointment->status ?? 'Completed') === 'No Show' ? 'danger' : 'secondary') }}">
                                                {{ $appointment->status ?? 'Completed' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('patient.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#rebook{{ $appointment->appointment_id }}">Rebook</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No past appointments</h5>
                        <p class="text-muted">Your appointment history will appear here</p>
                    </div>
                @endif
            </div>
            
            <!-- Cancelled Appointments Tab -->
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <h4 class="mb-4">Cancelled Appointments</h4>
                
                @if(isset($cancelledAppointments) && count($cancelledAppointments) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Scheduled Date</th>
                                    <th>Service</th>
                                    <th>Cancelled On</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cancelledAppointments as $appointment)
                                    <tr>
                                        <td>
                                            {{ date('M d, Y', strtotime($appointment->appointment_date ?? now()->addDays(rand(5, 20)))) }}
                                            <div class="text-muted small">{{ date('g:i A', strtotime($appointment->appointment_date ?? now()->addDays(rand(5, 20)))) }}</div>
                                        </td>
                                        <td>{{ $appointment->reason_for_visit }}</td>
                                        <td>{{ date('M d, Y', strtotime($appointment->cancelled_date ?? now()->subDays(rand(1, 5)))) }}</td>
                                        <td>{{ $appointment->cancellation_reason ?? 'Personal emergency' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rebook{{ $appointment->appointment_id }}">
                                                Reschedule
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No cancelled appointments</h5>
                        <p class="text-muted">You don't have any cancelled appointments</p>
                    </div>
                @endif
            </div>
            
            <!-- Calendar View Tab -->
            <div class="tab-pane fade" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-outline-secondary btn-sm me-2" id="prevMonth">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" id="nextMonth">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                            <h5 class="mb-0" id="currentMonth">June 2024</h5>
                            <button class="btn btn-primary btn-sm" id="todayBtn">Today</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Calendar Header -->
                        <div class="row mb-3">
                            <div class="col text-center">
                                <strong>Sun</strong>
                            </div>
                            <div class="col text-center">
                                <strong>Mon</strong>
                            </div>
                            <div class="col text-center">
                                <strong>Tue</strong>
                            </div>
                            <div class="col text-center">
                                <strong>Wed</strong>
                            </div>
                            <div class="col text-center">
                                <strong>Thu</strong>
                            </div>
                            <div class="col text-center">
                                <strong>Fri</strong>
                            </div>
                            <div class="col text-center">
                                <strong>Sat</strong>
                            </div>
                        </div>
                        
                        <!-- Calendar Grid - Sample for June 2024 -->
                        <div class="row mb-2">
                            <!-- Week 1 -->
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">26</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">27</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">28</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">29</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">30</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">31</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">1</div>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <!-- Week 2 -->
                            <div class="col p-1 text-center">
                                <div class="calendar-day">2</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">3</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">4</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">5</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day has-appointment" data-bs-toggle="tooltip" title="Tooth Cleaning - 10:30 AM">6</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">7</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">8</div>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <!-- Week 3 -->
                            <div class="col p-1 text-center">
                                <div class="calendar-day">9</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">10</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">11</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">12</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day today">13</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">14</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">15</div>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <!-- Week 4 -->
                            <div class="col p-1 text-center">
                                <div class="calendar-day">16</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">17</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">18</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">19</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">20</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day has-appointment" data-bs-toggle="tooltip" title="Regular Checkup - 2:00 PM">21</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">22</div>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <!-- Week 5 -->
                            <div class="col p-1 text-center">
                                <div class="calendar-day">23</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">24</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">25</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">26</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">27</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">28</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day">29</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Week 6 -->
                            <div class="col p-1 text-center">
                                <div class="calendar-day">30</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">1</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">2</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">3</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">4</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">5</div>
                            </div>
                            <div class="col p-1 text-center">
                                <div class="calendar-day text-muted">6</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex align-items-center">
                            <div class="me-3 d-flex align-items-center">
                                <div class="appointment-status status-confirmed me-2"></div>
                                <span>Scheduled Appointment</span>
                            </div>
                            <div class="me-3 d-flex align-items-center">
                                <div style="width: 10px; height: 10px; border: 2px solid #1976d2; border-radius: 50%;" class="me-2"></div>
                                <span>Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips for calendar days with appointments
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush