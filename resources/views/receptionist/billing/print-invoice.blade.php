@extends('layouts.receptionist')

@section('title', 'Print Invoice')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Invoice #{{ $billing->invoice_number }}</h5>
                <div>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-1"></i> Print Invoice
                    </button>
                    <a href="{{ route('receptionist.billing.show', $billing) }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="invoice-print">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <h2 class="mb-1">INVOICE</h2>
                            <h6 class="text-muted mb-3">Invoice #{{ $billing->invoice_number }}</h6>
                            <address>
                                <strong>Navila Dental Clinic</strong><br>
                                123 Main Street<br>
                                City, State ZIP<br>
                                Phone: (123) 456-7890<br>
                                Email: info@naviladental.com
                            </address>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="mb-1">Invoice Date:</h6>
                            <p>{{ $billing->invoice_date ? $billing->invoice_date->format('F d, Y') : 'N/A' }}</p>
                            
                            <h6 class="mb-1">Due Date:</h6>
                            <p>{{ $billing->due_date ? $billing->due_date->format('F d, Y') : 'N/A' }}</p>
                            
                            <h6 class="mb-1">Status:</h6>
                            <span class="badge bg-{{ $billing->payment_status == 'Paid' ? 'success' : ($billing->payment_status == 'Partial' ? 'warning' : 'danger') }}">
                                {{ $billing->payment_status }}
                            </span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Patient Information -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <h6 class="mb-1">Bill To:</h6>
                            <address>
                                <strong>{{ $billing->patient->first_name }} {{ $billing->patient->last_name }}</strong><br>
                                {{ $billing->patient->address }}<br>
                                Phone: {{ $billing->patient->contact_number }}<br>
                                Email: {{ $billing->patient->email }}
                            </address>
                        </div>
                        <div class="col-6">
                            @if($billing->treatment)
                            <h6 class="mb-1">Treatment Details:</h6>
                            <p>
                                <strong>{{ $billing->treatment->name }}</strong><br>
                                Dentist: Dr. {{ optional($billing->treatment->appointment->dentist)->last_name }}<br>
                                Date: {{ $billing->treatment->treatment_date ? \Carbon\Carbon::parse($billing->treatment->treatment_date)->format('M d, Y') : 'N/A' }}
                            </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Invoice Items -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <strong>{{ $billing->description }}</strong>
                                        @if($billing->treatment)
                                        <br>
                                        <small class="text-muted">{{ $billing->treatment->description }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">1</td>
                                    <td class="text-end">₱ {{ number_format($billing->amount_due, 2) }}</td>
                                    <td class="text-end">₱ {{ number_format($billing->amount_due, 2) }}</td>
                                </tr>
                                
                                <!-- Additional items if any -->
                                @if($billing->additional_charges > 0)
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <strong>Additional Charges</strong>
                                        <br>
                                        <small class="text-muted">{{ $billing->additional_charges_description ?? 'Extra charges' }}</small>
                                    </td>
                                    <td class="text-center">1</td>
                                    <td class="text-end">₱ {{ number_format($billing->additional_charges, 2) }}</td>
                                    <td class="text-end">₱ {{ number_format($billing->additional_charges, 2) }}</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                @if($billing->discount > 0)
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">₱ {{ number_format($billing->amount_due + $billing->additional_charges, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-end"><strong>Discount:</strong></td>
                                    <td class="text-end">- ₱ {{ number_format($billing->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end">₱ {{ number_format($billing->amount_due + $billing->additional_charges - $billing->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-end"><strong>Paid Amount:</strong></td>
                                    <td class="text-end">₱ {{ number_format($billing->amount_paid, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-end"><strong>Balance Due:</strong></td>
                                    <td class="text-end">
                                        <strong>₱ {{ number_format(($billing->amount_due + $billing->additional_charges - $billing->discount) - $billing->amount_paid, 2) }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Payment Information -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <h6 class="mb-2">Payment Method:</h6>
                            <p>{{ $billing->payment_method ?? 'Not specified' }}</p>
                            
                            @if($billing->payment_status == 'Paid' || $billing->payment_status == 'Partial')
                            <h6 class="mb-2">Payment Date:</h6>
                            <p>{{ $billing->payment_date ? $billing->payment_date->format('F d, Y') : ($billing->updated_at ? $billing->updated_at->format('F d, Y') : 'N/A') }}</p>
                            @endif
                        </div>
                        <div class="col-6">
                            <h6 class="mb-2">Payment Instructions:</h6>
                            <p>
                                Please make payment before the due date.<br>
                                For any inquiries about this invoice, please contact our billing department.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-2">Notes:</h6>
                            <p>{{ $billing->notes ?? 'Thank you for choosing Navila Dental Clinic for your dental care.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style type="text/css">
    @media print {
        body * {
            visibility: hidden;
        }
        .invoice-print, .invoice-print * {
            visibility: visible;
        }
        .invoice-print {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 40px;
        }
        .card-header, .btn, .no-print {
            display: none !important;
        }
    }
</style>
@endpush
@endsection