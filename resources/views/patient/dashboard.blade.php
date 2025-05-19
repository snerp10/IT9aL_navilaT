@extends('layouts.patient')

@section('content')
<div class="container">
    <!-- Debug output for upcoming appointments -->
    <div class="alert alert-info">
        <strong>Debug:</strong> Upcoming Appointments Count: {{ $upcomingAppointments->count() }}<br>
        @foreach($upcomingAppointments as $appt)
            [ID: {{ $appt->appointment_id }} | Date: {{ $appt->appointment_date }} | Status: {{ $appt->status }}]<br>
        @endforeach
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Patient Dashboard</h4>
                    <p class="text-muted">Welcome, {{ $patient->first_name }} {{ $patient->last_name }}</p>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Upcoming Appointments Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5>Upcoming Appointments</h5>
                                </div>
                                <div class="card-body">
                                    @if($upcomingAppointments->isEmpty())
                                        <p>You don't have any upcoming appointments.</p>
                                        <a href="{{ route('patient.appointments.book-form') }}" class="btn btn-primary">Book an Appointment</a>
                                    @else
                                        <div class="list-group">
                                            @foreach($upcomingAppointments as $appointment)
                                                <div class="list-group-item">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</h6>
                                                        <span class="badge {{ $appointment->status === 'Scheduled' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $appointment->status }}
                                                        </span>
                                                    </div>
                                                    <p class="mb-1">{{ $appointment->reason_for_visit }}</p>
                                                    <small>With Dr. {{ $appointment->dentist->user->name ?? 'Not Assigned' }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-primary">View All Appointments</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Billings Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5>Recent Billings</h5>
                                </div>
                                <div class="card-body">
                                    @if($pendingBills->isEmpty())
                                        <p>You don't have any recent billing records.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingBills as $billing)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}</td>
                                                            <td>{{ $billing->description }}</td>
                                                            <td>${{ number_format($billing->total_amount, 2) }}</td>
                                                            <td>
                                                                <span class="badge {{ $billing->payment_status === 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $billing->payment_status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('patient.billings') }}" class="btn btn-sm btn-primary">View All Billings</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Treatments Section -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Treatments</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentTreatments->isEmpty())
                                        <p>You don't have any treatment records yet.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Treatment</th>
                                                        <th>Dentist</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentTreatments as $treatment)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($treatment->treatment_date)->format('M d, Y') }}</td>
                                                            <td>{{ $treatment->service->name ?? $treatment->treatment_name }}</td>
                                                            <td>Dr. {{ $treatment->dentist->user->name ?? 'Not Available' }}</td>
                                                            <td>{{ \Illuminate\Support\Str::limit($treatment->notes, 50) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('patient.treatments') }}" class="btn btn-sm btn-primary">View All Treatments</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection