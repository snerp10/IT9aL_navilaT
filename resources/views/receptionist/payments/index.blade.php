@extends('layouts.receptionist')

@section('title', 'Payment Processing')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Processing</h5>
                    <a href="{{ route('receptionist.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body p-3">
                    <!-- Status Messages -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Error Messages -->
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Pending Payments -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Pending Payments</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Invoice #</th>
                                                    <th>Patient</th>
                                                    <th>Date</th>
                                                    <th>Amount Due</th>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($pendingPayments as $billing)
                                                <tr>
                                                    <td>{{ $billing->billing_id }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $billing->patient->first_name }} {{ $billing->patient->last_name }}</span>
                                                            <small>{{ $billing->patient->contact_number }}</small>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}</td>
                                                    <td>₱{{ number_format($billing->amount_due, 2) }}</td>
                                                    <td>₱{{ number_format($billing->amount_paid, 2) }}</td>
                                                    <td>₱{{ number_format($billing->amount_due - $billing->amount_paid, 2) }}</td>
                                                    <td>
                                                        @if($billing->payment_status == 'Pending')
                                                            <span class="badge bg-warning">{{ $billing->payment_status }}</span>
                                                        @elseif($billing->payment_status == 'Partial')
                                                            <span class="badge bg-info">{{ $billing->payment_status }}</span>
                                                        @elseif($billing->payment_status == 'Paid')
                                                            <span class="badge bg-success">{{ $billing->payment_status }}</span>
                                                        @elseif($billing->payment_status == 'Overdue')
                                                            <span class="badge bg-danger">{{ $billing->payment_status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $billing->payment_status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('receptionist.billing.process-payment', $billing) }}" class="btn btn-success">
                                                                <i class="fas fa-money-bill-wave me-1"></i> Process Payment
                                                            </a>
                                                            <a href="{{ route('receptionist.billing.print-invoice', $billing) }}" class="btn btn-info ms-2">
                                                                <i class="fas fa-file-invoice me-1"></i> View Invoice
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">No pending payments found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $pendingPayments->links('vendor.pagination.custom-theme') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Payment Form - Alternative payment method -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Payment Processing</h5>
                                </div>
                                <div class="card-body">
                                    <form id="searchForm" class="mb-4" action="{{ route('receptionist.payments') }}" method="GET">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    <input type="text" name="query" class="form-control" placeholder="Search by patient name, ID, or invoice number..." value="{{ request('query') }}">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                
                                    <p class="text-muted mb-4">
                                        To quickly process a payment, search for the patient or invoice above, or locate the specific invoice
                                        in the pending payments list and click "Process Payment".
                                    </p>
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Payment Methods Accepted:</h6>
                                            <ul class="list-group mb-4">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-money-bill text-success me-2"></i> Cash
                                                    </div>
                                                    <span class="badge bg-success rounded-pill">Instant</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fab fa-cc-visa text-primary me-2"></i> Credit/Debit Card
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill">POS</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-mobile-alt text-info me-2"></i> GCash
                                                    </div>
                                                    <span class="badge bg-info rounded-pill">QR Code</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-mobile-alt text-danger me-2"></i> Maya
                                                    </div>
                                                    <span class="badge bg-danger rounded-pill">QR Code</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Payment Processing Tips:</h6>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i> Always verify patient identity before processing payment
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i> Confirm the amount due with the patient
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i> Provide a receipt for all payments
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i> For partial payments, clearly explain the remaining balance
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i> Record the payment method used
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection