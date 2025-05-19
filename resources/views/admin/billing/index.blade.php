@extends('layouts.admin')

@section('title', isset($title) ? $title : 'All Invoices')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ isset($title) ? $title : 'All Invoices' }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{ isset($title) ? $title : 'All Invoices' }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-receipt me-1"></i> 
                {{ isset($title) ? $title : 'All Invoices' }}
            </div>
            <div>
                <a href="{{ route('billing.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Invoice
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="btn-group" role="group">
                        <a href="{{ route('billing.index') }}" class="btn btn-outline-primary {{ !isset($status) ? 'active' : '' }}">
                            All Invoices
                        </a>
                        <a href="{{ route('billing.pending') }}" class="btn btn-outline-warning {{ isset($status) && $status == 'pending' ? 'active' : '' }}">
                            Pending Payments
                        </a>
                        <a href="{{ route('billing.completed') }}" class="btn btn-outline-success {{ isset($status) && $status == 'paid' ? 'active' : '' }}">
                            Completed Payments
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Billings Table -->
            @if($billings->isEmpty())
                <div class="alert alert-info">
                    No invoices found in this category.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Patient</th>
                                <th>Treatment</th>
                                <th>Amount Due</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($billings as $billing)
                                <tr class="{{ $billing->payment_status === 'Overdue' ? 'table-danger' : ($billing->payment_status === 'Paid' ? 'table-success' : '') }}">
                                    <td>{{ $billing->invoice_number }}</td>
                                    <td>
                                        <a href="{{ route('patients.show', $billing->patient->patient_id) }}">
                                            {{ $billing->patient->last_name }}, {{ $billing->patient->first_name }}
                                        </a>
                                    </td>
                                    <td>{{ $billing->treatment->treatment_name ?? 'N/A' }}</td>
                                    <td class="text-end">₱{{ number_format($billing->amount_due, 2) }}</td>
                                    <td class="text-end">₱{{ number_format($billing->amount_paid, 2) }}</td>
                                    <td>
                                        @if($billing->payment_status === 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($billing->payment_status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td>{{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('billing.show', $billing->billing_id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('billing.edit', $billing->billing_id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($billing->payment_status !== 'Paid')
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#processPaymentModal" 
                                                    data-billing-id="{{ $billing->billing_id }}"
                                                    data-amount-due="{{ $billing->amount_due - $billing->amount_paid }}"
                                                    title="Process Payment">
                                                    <i class="bi bi-credit-card"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('billing.generate-invoice', $billing->billing_id) }}" class="btn btn-sm btn-secondary" title="Generate Invoice">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Process Payment Modal -->
<div class="modal fade" id="processPaymentModal" tabindex="-1" aria-labelledby="processPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processPaymentModalLabel">Process Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Amount to Pay</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" required>
                        </div>
                        <small class="form-text text-muted">Amount due: ₱<span id="amountDueText">0.00</span></small>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select payment method</option>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle payment modal
        const processPaymentModal = document.getElementById('processPaymentModal');
        if (processPaymentModal) {
            processPaymentModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const billingId = button.getAttribute('data-billing-id');
                const amountDue = button.getAttribute('data-amount-due');
                
                const form = this.querySelector('#paymentForm');
                form.action = `/billing/${billingId}/process-payment`;
                
                const amountInput = this.querySelector('#amount_paid');
                amountInput.value = amountDue;
                amountInput.max = amountDue;
                
                const amountDueText = this.querySelector('#amountDueText');
                amountDueText.textContent = parseFloat(amountDue).toFixed(2);
            });
        }
    });
</script>
@endsection
@endsection
