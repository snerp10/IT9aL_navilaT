@extends('layouts.receptionist')

@section('title', 'Process Payment')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Process Payment for Invoice #{{ $billing->invoice_number }}</h5>
                <div>
                    <a href="{{ route('receptionist.billing.show', $billing) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Invoice
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

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row">
                    <!-- Billing Summary -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Invoice Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Patient:</div>
                                    <div class="col-md-7">{{ $billing->patient->first_name }} {{ $billing->patient->last_name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Invoice Number:</div>
                                    <div class="col-md-7">{{ $billing->invoice_number }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Description:</div>
                                    <div class="col-md-7">{{ $billing->description }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Invoice Date:</div>
                                    <div class="col-md-7">{{ $billing->invoice_date ? $billing->invoice_date->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Due Date:</div>
                                    <div class="col-md-7">{{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Original Amount Due:</div>
                                    <div class="col-md-7">₱{{ number_format($billing->amount_due, 2) }}</div>
                                </div>
                                
                                @if($billing->additional_charges > 0)
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Additional Charges:</div>
                                    <div class="col-md-7">₱{{ number_format($billing->additional_charges, 2) }}</div>
                                </div>
                                @endif
                                
                                @if($billing->discount > 0)
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Discount:</div>
                                    <div class="col-md-7">₱{{ number_format($billing->discount, 2) }}</div>
                                </div>
                                @endif
                                
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Total Amount Due:</div>
                                    <div class="col-md-7 fw-bold">₱{{ number_format($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0), 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Amount Already Paid:</div>
                                    <div class="col-md-7 text-success">₱{{ number_format($billing->amount_paid, 2) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Current Balance:</div>
                                    <div class="col-md-7 text-danger fw-bold">
                                        ₱{{ number_format(($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0)) - $billing->amount_paid, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-5 fw-bold">Current Status:</div>
                                    <div class="col-md-7">
                                        @if($billing->payment_status == 'Paid')
                                            <span class="badge bg-success">{{ $billing->payment_status }}</span>
                                        @elseif($billing->payment_status == 'Partial')
                                            <span class="badge bg-warning text-dark">{{ $billing->payment_status }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $billing->payment_status }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Form -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Payment Details</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('receptionist.billing.process', $billing) }}" method="POST">
                                    @csrf
                                    
                                    <!-- Amount Paid -->
                                    <div class="mb-3">
                                        <label for="amount_paid" class="form-label">Amount Being Paid <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" step="0.01" min="0" 
                                                class="form-control @error('amount_paid') is-invalid @enderror" 
                                                id="amount_paid" name="amount_paid" 
                                                value="{{ old('amount_paid', ($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0)) - $billing->amount_paid) }}" 
                                                required>
                                        </div>
                                        @error('amount_paid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Remaining balance: ₱{{ number_format(($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0)) - $billing->amount_paid, 2) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Method -->
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                            <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                            <option value="GCash" {{ old('payment_method') == 'GCash' ? 'selected' : '' }}>GCash</option>
                                            <option value="Maya" {{ old('payment_method') == 'Maya' ? 'selected' : '' }}>Maya</option>
                                            <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="Check" {{ old('payment_method') == 'Check' ? 'selected' : '' }}>Check</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Payment Date -->
                                    <div class="mb-3">
                                        <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                            id="payment_date" name="payment_date" 
                                            value="{{ old('payment_date', date('Y-m-d')) }}" 
                                            required>
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Payment Notes -->
                                    <div class="mb-3">
                                        <label for="payment_notes" class="form-label">Payment Notes</label>
                                        <textarea class="form-control @error('payment_notes') is-invalid @enderror" 
                                            id="payment_notes" name="payment_notes" rows="3">{{ old('payment_notes') }}</textarea>
                                        @error('payment_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-money-bill-wave me-1"></i> Process Payment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Payment Guidelines -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Payment Guidelines</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>Verify patient identity before processing payment.</li>
                                    <li>Ensure that the correct amount is being paid.</li>
                                    <li>For partial payments, clearly explain the remaining balance to the patient.</li>
                                    <li>Provide a receipt after processing the payment.</li>
                                    <li>For card payments, ensure the card belongs to the patient or an authorized person.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-set current date for payment_date field
        if (!document.getElementById('payment_date').value) {
            const today = new Date();
            const formattedDate = today.toISOString().substr(0, 10);
            document.getElementById('payment_date').value = formattedDate;
        }
    });
</script>
@endpush