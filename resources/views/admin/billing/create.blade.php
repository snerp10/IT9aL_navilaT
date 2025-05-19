@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Create New Invoice</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
        <li class="breadcrumb-item active">Create Invoice</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-receipt me-1"></i> New Invoice Details
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('billing.store') }}" class="needs-validation" novalidate>
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="invoice_number" class="form-label">Invoice Number</label>
                            <div class="input-group">
                                <span class="input-group-text">INV-</span>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ 'INV-' . date('Ymd') . rand(1000, 9999) }}" required>
                            </div>
                            <div class="invalid-feedback">Please provide an invoice number.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select class="form-select" id="patient_id" name="patient_id" required>
                                <option value="">Select Patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->patient_id }}">
                                        {{ $patient->last_name }}, {{ $patient->first_name }} 
                                        (ID: {{ $patient->patient_id }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a patient.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="treatment_id" class="form-label">Treatment/Service</label>
                            <select class="form-select" id="treatment_id" name="treatment_id" required>
                                <option value="">Select Treatment</option>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->treatment_id }}" data-cost="{{ $treatment->cost }}">
                                        {{ $treatment->treatment_name }} 
                                        - ₱{{ number_format($treatment->cost, 2) }}
                                        @if($treatment->appointment)
                                            ({{ $treatment->appointment->appointment_date->format('M d, Y') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a treatment.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="amount_due" class="form-label">Amount Due</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="amount_due" name="amount_due" step="0.01" required>
                            </div>
                            <div class="invalid-feedback">Please enter the amount due.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount_paid" class="form-label">Initial Payment</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="amount_paid" name="amount_paid" value="0" step="0.01" required>
                            </div>
                            <div class="invalid-feedback">Please enter any initial payment.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_status" class="form-label">Payment Status</label>
                                    <select class="form-select" id="payment_status" name="payment_status" required>
                                        <option value="Pending" selected>Pending</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Overdue">Overdue</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a payment status.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="Cash" selected>Cash</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="PayPal">PayPal</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a payment method.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                value="{{ date('Y-m-d', strtotime('+15 days')) }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('billing.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update amount due based on selected treatment
        const treatmentSelect = document.getElementById('treatment_id');
        const amountDueInput = document.getElementById('amount_due');
        
        treatmentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const cost = selectedOption.getAttribute('data-cost');
                if (cost) {
                    amountDueInput.value = parseFloat(cost).toFixed(2);
                }
            } else {
                amountDueInput.value = '';
            }
        });
        
        // Form validation
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>
@endsection
@endsection
