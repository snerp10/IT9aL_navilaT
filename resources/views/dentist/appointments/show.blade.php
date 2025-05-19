@extends('layouts.dentist')

@section('title', 'Appointment Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Appointment Details</h1>
        <div>
            <a href="{{ route('dentist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Appointments
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Appointment Information -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Appointment Details</h5>
                        <div>
                            @if(in_array($appointment->status, ['Scheduled', 'Confirmed']))
                                <span class="badge bg-primary">{{ $appointment->status }}</span>
                            @elseif($appointment->status == 'In Progress')
                                <span class="badge bg-warning">{{ $appointment->status }}</span>
                            @elseif($appointment->status == 'Completed')
                                <span class="badge bg-success">{{ $appointment->status }}</span>
                            @elseif($appointment->status == 'Canceled')
                                <span class="badge bg-danger">{{ $appointment->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $appointment->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Date & Time</p>
                            <p class="fs-5">{{ $appointment->appointment_date->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Reason for Visit</p>
                            <p class="fs-5">{{ $appointment->reason_for_visit }}</p>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-muted mb-1">Notes</p>
                            <p>{{ $appointment->notes ?? 'No notes added.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatments Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Treatments</h5>
                        <a href="{{ route('dentist.treatments.create', ['appointment_id' => $appointment->appointment_id]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Treatment
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($appointment->treatments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Treatment</th>
                                        <th>Tooth #</th>
                                        <th>Status</th>
                                        <th>Cost</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointment->treatments as $treatment)
                                        <tr>
                                            <td>
                                                <strong>{{ $treatment->name }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($treatment->description, 50) }}</small>
                                            </td>
                                            <td>{{ $treatment->tooth_number ?? 'N/A' }}</td>
                                            <td>
                                                @if($treatment->status == 'Planned')
                                                    <span class="badge bg-info">{{ $treatment->status }}</span>
                                                @elseif($treatment->status == 'In Progress')
                                                    <span class="badge bg-warning">{{ $treatment->status }}</span>
                                                @elseif($treatment->status == 'Completed')
                                                    <span class="badge bg-success">{{ $treatment->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $treatment->status }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($treatment->cost, 2) }}</td>
                                            <td>
                                                <a href="{{ route('dentist.treatments.show', $treatment) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                @if(!$treatment->billings()->exists())
                                                    <a href="{{ route('dentist.treatments.edit', $treatment) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                            <p class="mt-3">No treatments have been added to this appointment yet.</p>
                            <a href="{{ route('dentist.treatments.create', ['appointment_id' => $appointment->appointment_id]) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Treatment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Patient Information and Actions -->
        <div class="col-md-4">
            <!-- Patient Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <span class="fs-4">{{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-person me-1"></i> 
                                {{ $appointment->patient->gender }}, 
                                {{ \Carbon\Carbon::parse($appointment->patient->birth_date)->age }} years
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-telephone me-2"></i>Contact Number</p>
                        <p class="mb-0">{{ $appointment->patient->contact_number }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-envelope me-2"></i>Email</p>
                        <p class="mb-0">{{ $appointment->patient->email }}</p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('dentist.patients.show', $appointment->patient) }}" class="btn btn-outline-primary">
                            <i class="bi bi-person"></i> View Patient Profile
                        </a>
                        <a href="{{ route('dentist.patients.dental-chart', $appointment->patient) }}" class="btn btn-outline-info">
                            <i class="bi bi-grid-3x3"></i> View Dental Chart
                        </a>
                    </div>
                </div>
            </div>

            <!-- Appointment Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dentist.appointments.update-status', $appointment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Appointment Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="Scheduled" {{ $appointment->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="In Progress" {{ $appointment->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Canceled" {{ $appointment->status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="No Show" {{ $appointment->status == 'No Show' ? 'selected' : '' }}>No Show</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Add Note</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Add a note about this appointment..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection