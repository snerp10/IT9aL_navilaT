@extends('layouts.admin')

@section('title', 'Payment Processing')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Payment Processing</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
            <li class="breadcrumb-item active">Payment Processing</li>
        </ol>

        <div class="row">
            <!-- Payment Statistics -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Today's Payments</h5>
                        <h2 class="display-6">₱{{ number_format($paymentStats['total_paid_today'], 2) }}</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('billing.completed') }}">View Details</a>
                        <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-dark mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pending Payments</h5>
                        <h2 class="display-6">{{ $paymentStats['pending_count'] ?? 0 }}</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-dark stretched-link" href="{{ route('billing.pending') }}">View Details</a>
                        <div class="small text-dark"><i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-graph-up me-1"></i>
                        Payment Methods Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodsChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Processing Section -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-credit-card me-1"></i>
                        Process Payment
                    </div>
                    <div class="card-body">
                        <form id="paymentSearchForm" class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by invoice number or patient name..." id="searchInput">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bi bi-search"></i> Find Invoice
                                </button>
                            </div>
                        </form>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Search for an invoice above to process a payment, or browse through the pending payments list.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-lightning-charge me-1"></i>
                        Quick Actions
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('billing.pending') }}" class="btn btn-warning">
                                <i class="bi bi-hourglass-split"></i> View Pending Payments
                            </a>
                            <a href="{{ route('billing.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Create New Invoice
                            </a>
                            <button class="btn btn-info" id="generateReportBtn">
                                <i class="bi bi-file-earmark-text"></i> Generate Payment Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Payment History -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-clock-history me-1"></i>
                Recent Payment History
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->invoice_number }}</td>
                                    <td>
                                        @if(isset($payment->patient))
                                            {{ $payment->patient->first_name }} {{ $payment->patient->last_name }}
                                        @else
                                            Unknown Patient
                                        @endif
                                    </td>
                                    <td>{{ $payment->updated_at->format('M d, Y') }}</td>
                                    <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>
                                        @if($payment->payment_status == 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($payment->payment_status == 'Partial')
                                            <span class="badge bg-warning text-dark">Partial</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('billing.show', $payment->billing_id) }}" class="btn btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#processPaymentModal" data-billing-id="{{ $payment->billing_id }}">
                                                <i class="bi bi-credit-card"></i>
                                            </button>
                                            <a href="#" class="btn btn-secondary invoice-btn" data-billing-id="{{ $payment->billing_id }}">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No recent payment records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                <form id="processPaymentForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3" id="invoiceDetails">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Invoice:</strong>
                                <span id="invoiceNumber"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Patient:</strong>
                                <span id="patientName"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total Amount:</strong>
                                <span id="totalAmount"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Amount Paid:</strong>
                                <span id="amountPaid"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Balance:</strong>
                                <span id="remainingBalance"></span>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Payment Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                                <option value="Maya">Maya</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle payment modal
            const processPaymentModal = document.getElementById('processPaymentModal');
            if (processPaymentModal) {
                processPaymentModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const billingId = button.getAttribute('data-billing-id');
                    
                    // Here you would typically fetch the billing details from your API
                    // For demonstration, we'll just set the action and some placeholder data
                    
                    const form = document.getElementById('processPaymentForm');
                    form.action = `/admin/billing/${billingId}/process-payment`;
                    
                    // Set placeholder data (in a real app, you would fetch this data)
                    document.getElementById('invoiceNumber').textContent = 'INV-' + Math.floor(1000 + Math.random() * 9000);
                    document.getElementById('patientName').textContent = 'John Doe';
                    document.getElementById('totalAmount').textContent = '₱1,500.00';
                    document.getElementById('amountPaid').textContent = '₱500.00';
                    document.getElementById('remainingBalance').textContent = '₱1,000.00';
                    
                    // Set the default amount to the remaining balance
                    document.getElementById('amount_paid').value = 1000.00;
                });
            }
            
            // Initialize Payment Methods Chart
            const ctx = document.getElementById('paymentMethodsChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Cash', 'GCash', 'Maya', 'Credit Card', 'Bank Transfer'],
                        datasets: [{
                            data: [45, 25, 15, 10, 5],
                            backgroundColor: [
                                '#28a745',
                                '#007bff',
                                '#17a2b8',
                                '#fd7e14',
                                '#6c757d'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right',
                            }
                        }
                    }
                });
            }
            
            // Search form handling
            const searchForm = document.getElementById('paymentSearchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const searchValue = document.getElementById('searchInput').value;
                    
                    // Here you would typically navigate to search results or show results directly
                    alert(`Searching for: ${searchValue}`);
                });
            }
            
            // Generate report button
            const reportBtn = document.getElementById('generateReportBtn');
            if (reportBtn) {
                reportBtn.addEventListener('click', function() {
                    // Here you would typically trigger a report generation
                    // For demo, we'll just show an alert
                    alert('Generating payment report...');
                });
            }
        });
    </script>
    @endsection
@endsection