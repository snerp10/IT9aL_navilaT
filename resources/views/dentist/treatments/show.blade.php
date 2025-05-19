@extends('layouts.dentist')

@section('title', 'Treatment Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Treatment Details</h1>
        <div>
            <a href="{{ route('dentist.appointments.show', $treatment->appointment) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Appointment
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Treatment Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Treatment Information</h5>
                        <div>
                            @if($treatment->status == 'Planned')
                                <span class="badge bg-info">{{ $treatment->status }}</span>
                            @elseif($treatment->status == 'In Progress')
                                <span class="badge bg-warning">{{ $treatment->status }}</span>
                            @elseif($treatment->status == 'Completed')
                                <span class="badge bg-success">{{ $treatment->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $treatment->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Treatment Name</p>
                            <p class="fs-5">{{ $treatment->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Cost</p>
                            <p class="fs-5">${{ number_format($treatment->cost, 2) }}</p>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Treatment Date</p>
                            <p>{{ $treatment->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Tooth Number</p>
                            <p>{{ $treatment->tooth_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Dental Service</p>
                            <p>{{ $treatment->dentalService->name ?? 'Custom Treatment' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Duration</p>
                            <p>{{ $treatment->duration ? $treatment->duration . ' minutes' : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1">Description</p>
                        <p>{{ $treatment->description }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1">Clinical Notes</p>
                        <p>{{ $treatment->notes ?? 'No clinical notes added.' }}</p>
                    </div>
                    
                    @if(!$treatment->billings()->exists())
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('dentist.treatments.edit', $treatment) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Treatment
                        </a>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This treatment has billing records associated with it and cannot be edited.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Patient Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <span class="fs-4">{{ substr($treatment->patient->first_name, 0, 1) }}{{ substr($treatment->patient->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $treatment->patient->first_name }} {{ $treatment->patient->last_name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-person me-1"></i> 
                                {{ $treatment->patient->gender }}, 
                                {{ \Carbon\Carbon::parse($treatment->patient->birth_date)->age }} years
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-telephone me-2"></i>Contact Number</p>
                        <p class="mb-0">{{ $treatment->patient->contact_number }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-envelope me-2"></i>Email</p>
                        <p class="mb-0">{{ $treatment->patient->email }}</p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('dentist.patients.show', $treatment->patient) }}" class="btn btn-outline-primary">
                            <i class="bi bi-person"></i> View Patient Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Appointment Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-calendar me-2"></i>Date & Time</p>
                        <p class="mb-0">{{ $treatment->appointment->appointment_date->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-info-circle me-2"></i>Reason for Visit</p>
                        <p class="mb-0">{{ $treatment->appointment->reason_for_visit }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-tag me-2"></i>Status</p>
                        <p class="mb-0">
                            @if($treatment->appointment->status == 'Scheduled')
                                <span class="badge bg-primary">{{ $treatment->appointment->status }}</span>
                            @elseif($treatment->appointment->status == 'In Progress')
                                <span class="badge bg-warning">{{ $treatment->appointment->status }}</span>
                            @elseif($treatment->appointment->status == 'Completed')
                                <span class="badge bg-success">{{ $treatment->appointment->status }}</span>
                            @elseif($treatment->appointment->status == 'Canceled')
                                <span class="badge bg-danger">{{ $treatment->appointment->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $treatment->appointment->status }}</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('dentist.appointments.show', $treatment->appointment) }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar"></i> View Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection