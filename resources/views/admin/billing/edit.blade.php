@extends('layouts.admin')

@section('title', 'Edit Billing')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Billing</h1>
        <a href="{{ route('billing.show', $billing->billing_id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Billing
        </a>
    </div>
    <form method="POST" action="{{ route('billing.update', $billing->billing_id) }}">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="patient_id" class="form-label">Patient</label>
                <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->patient_id }}" {{ $billing->patient_id == $patient->patient_id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="treatment_id" class="form-label">Treatment</label>
                <select class="form-select @error('treatment_id') is-invalid @enderror" id="treatment_id" name="treatment_id" required>
                    @foreach ($treatments as $treatment)
                        <option value="{{ $treatment->treatment_id }}" {{ $billing->treatment_id == $treatment->treatment_id ? 'selected' : '' }}>
                            {{ $treatment->name }} ({{ $treatment->treatment_date }})
                        </option>
                    @endforeach
                </select>
                @error('treatment_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $billing->invoice_number) }}" required>
                @error('invoice_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="amount_due" class="form-label">Amount Due</label>
                <input type="number" step="0.01" class="form-control @error('amount_due') is-invalid @enderror" id="amount_due" name="amount_due" value="{{ old('amount_due', $billing->amount_due) }}" required>
                @error('amount_due')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="amount_paid" class="form-label">Amount Paid</label>
                <input type="number" step="0.01" class="form-control @error('amount_paid') is-invalid @enderror" id="amount_paid" name="amount_paid" value="{{ old('amount_paid', $billing->amount_paid) }}" required>
                @error('amount_paid')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="payment_status" class="form-label">Payment Status</label>
                <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                    <option value="Pending" {{ $billing->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Paid" {{ $billing->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Overdue" {{ $billing->payment_status == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                @error('payment_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                    <option value="Cash" {{ $billing->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="GCash" {{ $billing->payment_method == 'GCash' ? 'selected' : '' }}>GCash</option>
                    <option value="Maya" {{ $billing->payment_method == 'Maya' ? 'selected' : '' }}>Maya</option>
                    <option value="PayPal" {{ $billing->payment_method == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $billing->due_date ? $billing->due_date->format('Y-m-d') : '') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Update Billing</button>
                <a href="{{ route('billing.show', $billing->billing_id) }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
