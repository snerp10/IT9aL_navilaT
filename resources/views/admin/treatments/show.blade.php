@extends('layouts.admin')

@section('title', 'Treatment Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Treatment Details</h1>
        <div>
            <a href="{{ route('treatments.edit', $treatment->treatment_id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Treatment
            </a>
            <a href="{{ route('treatments.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Treatment Information</h5>
                    <span class="badge bg-{{ 
                        $treatment->status === 'Completed' ? 'success' : 
                        ($treatment->status === 'In Progress' ? 'primary' : 'warning') 
                    }} fs-6">{{ $treatment->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Treatment Name</h6>
                            <p class="fs-5">{{ $treatment->treatment_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Dental Service</h6>
                            <p>
                                @if($treatment->dentalService)
                                    <a href="{{ route('services.show', $treatment->service_id) }}">
                                        {{ $treatment->dentalService->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Custom Treatment</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="text-muted">Description</h6>
                            <p>{{ $treatment->description }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted">Cost</h6>
                            <p class="fs-5">${{ number_format($treatment->cost, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Duration</h6>
                            <p>{{ $treatment->duration ?? 'N/A' }} {{ $treatment->duration ? 'minutes' : '' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Tooth Number</h6>
                            <p>{{ $treatment->tooth_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted">Clinical Notes</h6>
                            <div class="border p-3 rounded bg-light">
                                {!! nl2br(e($treatment->notes)) !!}
                                @if(!$treatment->notes)
                                    <em class="text-muted">No clinical notes recorded</em>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Billing Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Billing Information</h5>
                </div>
                <div class="card-body">
                    @if($treatment->billings->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($treatment->billings as $billing)
                                        <tr>
                                            <td>{{ $billing->invoice_number }}</td>
                                            <td>{{ $billing->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format($billing->amount_due, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $billing->payment_status === 'Paid' ? 'success' : 
                                                    ($billing->payment_status === 'Partially Paid' ? 'warning' : 'danger') 
                                                }}">{{ $billing->payment_status }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('billing.show', $billing->billing_id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-receipt"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-credit-card fs-1 text-muted mb-3"></i>
                            <p>No billing records associated with this treatment.</p>
                            <a href="{{ route('billing.create', ['treatment_id' => $treatment->treatment_id]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Create Billing Record
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Patient Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <span>{{ substr($treatment->patient->first_name, 0, 1) }}{{ substr($treatment->patient->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-0">
                                <a href="{{ route('patients.show', $treatment->patient_id) }}">
                                    {{ $treatment->patient->first_name }} {{ $treatment->patient->last_name }}
                                </a>
                            </h5>
                            <span class="text-muted">{{ $treatment->patient->patient_id }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <i class="bi bi-telephone me-2"></i> {{ $treatment->patient->contact_number }}
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-envelope me-2"></i> {{ $treatment->patient->email }}
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('patients.show', $treatment->patient_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-person"></i> Patient Profile
                        </a>
                        <a href="{{ route('treatments.patient-treatments', $treatment->patient_id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-clipboard2-pulse"></i> Treatment History
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Appointment Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Date & Time</h6>
                        <p class="mb-0 fs-5">{{ $treatment->appointment->appointment_date->format('M d, Y') }}</p>
                        <p>{{ $treatment->appointment->appointment_date->format('g:i A') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Dentist</h6>
                        <p>Dr. {{ $treatment->appointment->dentist->first_name }} {{ $treatment->appointment->dentist->last_name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge bg-{{ 
                            $treatment->appointment->status === 'Completed' ? 'success' : 
                            ($treatment->appointment->status === 'Canceled' ? 'danger' : 'info') 
                        }}">{{ $treatment->appointment->status }}</span>
                    </div>
                    
                    <a href="{{ route('appointments.show', $treatment->appointment_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-calendar-event"></i> View Appointment
                    </a>
                </div>
            </div>
            
            <!-- Treatment Timeline Card -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li class="timeline-item mb-3">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Treatment Created</h6>
                                <small class="text-muted">{{ $treatment->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </li>
                        
                        @if($treatment->status == 'In Progress' || $treatment->status == 'Completed')
                            <li class="timeline-item mb-3">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Treatment Started</h6>
                                    <small class="text-muted">{{ $treatment->updated_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </li>
                        @endif
                        
                        @if($treatment->status == 'Completed')
                            <li class="timeline-item mb-3">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Treatment Completed</h6>
                                    <small class="text-muted">{{ $treatment->updated_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </li>
                        @endif
                        
                        @foreach($treatment->billings as $billing)
                            <li class="timeline-item mb-3">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Invoice Generated</h6>
                                    <small class="text-muted">{{ $billing->created_at->format('M d, Y g:i A') }}</small>
                                    <p class="mb-0">Invoice #{{ $billing->invoice_number }}</p>
                                    <p class="mb-0">${{ number_format($billing->amount_due, 2) }}</p>
                                </div>
                            </li>
                            
                            @if($billing->payment_status == 'Paid')
                                <li class="timeline-item mb-3">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">Payment Received</h6>
                                        <small class="text-muted">{{ $billing->updated_at->format('M d, Y g:i A') }}</small>
                                        <p class="mb-0">${{ number_format($billing->amount_paid, 2) }}</p>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        list-style: none;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 7px;
        width: 2px;
        background-color: #e9ecef;
    }
    .timeline-marker {
        position: absolute;
        left: -8px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    .timeline-content {
        padding-left: 1rem;
    }
</style>
@endsection