@extends('layouts.admin')

@section('title', 'Invoice')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0">Invoice #{{ $billing->invoice_number }}</h5>
                    <div>
                        <button onclick="window.print()" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="text-primary mb-1">Dental Clinic</h4>
                            <p class="mb-0">123 Main Street</p>
                            <p class="mb-0">City, State 12345</p>
                            <p class="mb-0">Phone: (123) 456-7890</p>
                            <p class="mb-0">Email: info@dentalclinic.com</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="text-uppercase text-muted mb-2">Invoice</h5>
                            <p class="mb-0"><strong>Invoice #:</strong> {{ $billing->invoice_number }}</p>
                            <p class="mb-0"><strong>Date:</strong> {{ $billing->created_at->format('M d, Y') }}</p>
                            <p class="mb-0"><strong>Due Date:</strong> {{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}</p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge bg-{{ $billing->payment_status == 'Paid' ? 'success' : ($billing->payment_status == 'Overdue' ? 'danger' : 'warning') }}">
                                    {{ $billing->payment_status }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Patient Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-2">Bill To:</h6>
                            <p class="mb-0"><strong>{{ $billing->patient->full_name ?? 'Patient Name' }}</strong></p>
                            <p class="mb-0">ID: {{ $billing->patient->patient_id ?? 'N/A' }}</p>
                            <p class="mb-0">Email: {{ $billing->patient->email ?? 'N/A' }}</p>
                            <p class="mb-0">Phone: {{ $billing->patient->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Invoice Details -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        @if($billing->treatment)
                                            {{ $billing->treatment->description ?? 'Dental Treatment' }}
                                            @if($billing->treatment->tooth_number)
                                                <br><small>Tooth #{{ $billing->treatment->tooth_number }}</small>
                                            @endif
                                        @else
                                            Dental Services
                                        @endif
                                    </td>
                                    <td class="text-end">₱{{ number_format($billing->amount_due, 2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total Amount</th>
                                    <th class="text-end">₱{{ number_format($billing->amount_due, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">Amount Paid</th>
                                    <th class="text-end">₱{{ number_format($billing->amount_paid, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">Balance Due</th>
                                    <th class="text-end">₱{{ number_format($billing->amount_due - $billing->amount_paid, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Payment Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-2">Payment Method:</h6>
                            <p>{{ $billing->payment_method ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-2">Payment Instructions:</h6>
                            <p>Please make payment by due date. For questions regarding this invoice, please contact our billing department.</p>
                        </div>
                    </div>
                    
                    <!-- Notes & Terms -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-uppercase text-muted mb-2">Notes & Terms:</h6>
                            <p class="small">
                                1. Payment is due within 15 days of the invoice date.<br>
                                2. Please include the invoice number with your payment.<br>
                                3. A late fee may be applied to overdue accounts.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Thank You Note -->
                    <div class="row mt-4 pt-2">
                        <div class="col-md-12 text-center">
                            <p class="mb-0">Thank you for choosing our dental services!</p>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($billing->payment_status != 'Paid')
            <div class="text-center mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="bi bi-credit-card"></i> Make Payment
                </button>
            </div>
            
            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Process Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('billing.process-payment', $billing->billing_id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="amount_paid" class="form-label">Amount to Pay</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" min="0" max="{{ $billing->amount_due - $billing->amount_paid }}" 
                                            class="form-control" id="amount_paid" name="amount_paid" 
                                            value="{{ $billing->amount_due - $billing->amount_paid }}" required>
                                    </div>
                                    <div class="form-text">Remaining balance: ₱{{ number_format($billing->amount_due - $billing->amount_paid, 2) }}</div>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="Cash">Cash</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="PayPal">PayPal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Process Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style media="print">
    /* Print styles */
    .btn, .modal, footer, header, nav {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background-color: #fff !important;
        border-bottom: 1px solid #000 !important;
    }
    
    body {
        font-size: 12pt;
    }
    
    .table {
        border-collapse: collapse !important;
    }
    
    .table td, .table th {
        background-color: #fff !important;
        border: 1px solid #000 !important;
    }
</style>
@endsection