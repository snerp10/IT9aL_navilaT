@extends('layouts.receptionist')

@section('title', 'Invoice Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Invoice #{{ $billing->invoice_number }}</h5>
                <div>
                    @if($billing->payment_status != 'Paid')
                    <a href="{{ route('receptionist.billing.process-payment', $billing) }}" class="btn btn-success">
                        <i class="fas fa-money-bill-wave me-1"></i> Process Payment
                    </a>
                    @endif
                    <a href="{{ route('receptionist.billing.print-invoice', $billing) }}" class="btn btn-primary ms-2">
                        <i class="fas fa-print me-1"></i> Print Invoice
                    </a>
                    <a href="{{ route('receptionist.billing.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Status Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="row">
                    <!-- Invoice Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Invoice Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Invoice Number:</div>
                                    <div class="col-md-8">{{ $billing->invoice_number }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Invoice Date:</div>
                                    <div class="col-md-8">{{ $billing->invoice_date ? $billing->invoice_date->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Due Date:</div>
                                    <div class="col-md-8">{{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Description:</div>
                                    <div class="col-md-8">{{ $billing->description }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Amount Due:</div>
                                    <div class="col-md-8">₱{{ number_format($billing->amount_due, 2) }}</div>
                                </div>
                                
                                @if($billing->additional_charges > 0)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Additional Charges:</div>
                                    <div class="col-md-8">₱{{ number_format($billing->additional_charges, 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Charge Description:</div>
                                    <div class="col-md-8">{{ $billing->additional_charges_description ?? 'N/A' }}</div>
                                </div>
                                @endif
                                
                                @if($billing->discount > 0)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Discount:</div>
                                    <div class="col-md-8">₱{{ number_format($billing->discount, 2) }}</div>
                                </div>
                                @endif
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Total Amount:</div>
                                    <div class="col-md-8 fw-bold">₱{{ number_format($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0), 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Amount Paid:</div>
                                    <div class="col-md-8 text-success">₱{{ number_format($billing->amount_paid, 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Balance Due:</div>
                                    <div class="col-md-8 {{ ($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0) - $billing->amount_paid) > 0 ? 'text-danger' : '' }} fw-bold">
                                        ₱{{ number_format(($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0)) - $billing->amount_paid, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Payment Status:</div>
                                    <div class="col-md-8">
                                        @if($billing->payment_status == 'Paid')
                                            <span class="badge bg-success">{{ $billing->payment_status }}</span>
                                        @elseif($billing->payment_status == 'Partial')
                                            <span class="badge bg-warning text-dark">{{ $billing->payment_status }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $billing->payment_status }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($billing->payment_method)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Payment Method:</div>
                                    <div class="col-md-8">{{ $billing->payment_method }}</div>
                                </div>
                                @endif
                                @if($billing->payment_date)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Payment Date:</div>
                                    <div class="col-md-8">{{ $billing->payment_date->format('M d, Y') }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Patient and Treatment Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Patient Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Patient Name:</div>
                                    <div class="col-md-8">
                                        <a href="{{ route('receptionist.patients.show', $billing->patient) }}">
                                            {{ $billing->patient->first_name }} {{ $billing->patient->last_name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Patient ID:</div>
                                    <div class="col-md-8">{{ $billing->patient->patient_id }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Contact Number:</div>
                                    <div class="col-md-8">{{ $billing->patient->contact_number }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Email:</div>
                                    <div class="col-md-8">{{ $billing->patient->email }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($billing->treatment)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Treatment Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Treatment Name:</div>
                                    <div class="col-md-8">{{ $billing->treatment->name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Description:</div>
                                    <div class="col-md-8">{{ $billing->treatment->description }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Dentist:</div>
                                    <div class="col-md-8">
                                        @if($billing->treatment->appointment && $billing->treatment->appointment->dentist)
                                        Dr. {{ $billing->treatment->appointment->dentist->first_name }} {{ $billing->treatment->appointment->dentist->last_name }}
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Appointment Date:</div>
                                    <div class="col-md-8">
                                        @if($billing->treatment->appointment && $billing->treatment->appointment->appointment_date)
                                        {{ Carbon\Carbon::parse($billing->treatment->appointment->appointment_date)->format('M d, Y') }}
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Status:</div>
                                    <div class="col-md-8">
                                        <span class="badge bg-{{ $billing->treatment->status == 'Completed' ? 'success' : 'info' }}">
                                            {{ $billing->treatment->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Notes Section -->
                        @if($billing->notes)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Notes</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{!! nl2br(e($billing->notes)) !!}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                @if($billing->payment_status != 'Paid')
                                <a href="{{ route('receptionist.billing.edit', $billing) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i> Edit Invoice
                                </a>
                                @endif
                            </div>
                            <div>
                                @if($billing->payment_status != 'Paid')
                                <form action="{{ route('receptionist.billing.destroy', $billing) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-1"></i> Delete Invoice
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection