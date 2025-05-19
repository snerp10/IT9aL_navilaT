@extends('layouts.admin')

@section('title', 'Billing Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Billing Details</h1>
        <div>
            <a href="{{ route('billing.edit', $billing->billing_id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('billing.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Invoice #{{ $billing->invoice_number }}</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Patient</dt>
                        <dd class="col-sm-8">{{ $billing->patient->first_name }} {{ $billing->patient->last_name }}</dd>
                        <dt class="col-sm-4">Treatment</dt>
                        <dd class="col-sm-8">{{ $billing->treatment->name ?? '-' }}</dd>
                        <dt class="col-sm-4">Amount Due</dt>
                        <dd class="col-sm-8">${{ number_format($billing->amount_due, 2) }}</dd>
                        <dt class="col-sm-4">Amount Paid</dt>
                        <dd class="col-sm-8">${{ number_format($billing->amount_paid, 2) }}</dd>
                        <dt class="col-sm-4">Payment Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $billing->payment_status == 'Paid' ? 'success' : ($billing->payment_status == 'Overdue' ? 'danger' : 'warning') }}">
                                {{ $billing->payment_status }}
                            </span>
                        </dd>
                        <dt class="col-sm-4">Payment Method</dt>
                        <dd class="col-sm-8">{{ $billing->payment_method ?? '-' }}</dd>
                        <dt class="col-sm-4">Due Date</dt>
                        <dd class="col-sm-8">{{ $billing->due_date ? $billing->due_date->format('M d, Y') : '-' }}</dd>
                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $billing->created_at->format('M d, Y g:i A') }}</dd>
                        <dt class="col-sm-4">Last Updated</dt>
                        <dd class="col-sm-8">{{ $billing->updated_at->format('M d, Y g:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Patient Info</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $billing->patient->first_name }} {{ $billing->patient->last_name }}</strong></p>
                    <p class="mb-1"><i class="bi bi-envelope"></i> {{ $billing->patient->email }}</p>
                    <p class="mb-1"><i class="bi bi-telephone"></i> {{ $billing->patient->contact_number }}</p>
                </div>
            </div>
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Treatment Info</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $billing->treatment->name ?? '-' }}</strong></p>
                    <p class="mb-1">Cost: ${{ number_format($billing->treatment->cost ?? 0, 2) }}</p>
                    <p class="mb-1">Date: {{ $billing->treatment->treatment_date ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
