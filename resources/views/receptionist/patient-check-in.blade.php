@extends('layouts.receptionist')

@section('title', 'Patient Check-In')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Patient Check-In</h5>
            </div>
            <div class="card-body p-3">
                <!-- Status Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="appointmentSearch" class="form-control" placeholder="Search by patient name...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table" id="checkInTable">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient Name</th>
                                <th>Phone</th>
                                <th>Dentist</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAppointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</td>
                                <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                <td>{{ $appointment->patient->contact_number }}</td>
                                <td>Dr. {{ $appointment->dentist->last_name }}</td>
                                <td>
                                    @if($appointment->status == 'Scheduled')
                                        <span class="badge bg-warning">{{ $appointment->status }}</span>
                                    @elseif($appointment->status == 'In Progress')
                                        <span class="badge bg-info">{{ $appointment->status }}</span>
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
                                <td>
                                    <div class="d-flex justify-content-start">
                                        @if($appointment->status == 'Scheduled')
                                            <form action="{{ route('receptionist.process-check-in', $appointment) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success me-2">
                                                    <i class="fas fa-check-circle me-1"></i> Check In
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="btn btn-sm btn-secondary">
                                                <i class="fas fa-ban me-1"></i> Cannot Check In
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('receptionist.appointments.show', $appointment) }}" class="btn btn-sm btn-info ms-2">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No appointments scheduled for today</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple client-side search filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('appointmentSearch');
        const table = document.getElementById('checkInTable');
        const rows = table.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('keyup', function(e) {
            const searchText = e.target.value.toLowerCase();
            
            rows.forEach(row => {
                const patientName = row.cells[1].textContent.toLowerCase();
                const phone = row.cells[2].textContent.toLowerCase();
                
                if (patientName.includes(searchText) || phone.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection