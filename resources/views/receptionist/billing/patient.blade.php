@extends('layouts.receptionist')

@section('title', 'Patient Billing Records')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Billing Records for {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                    <p class="text-sm mb-0">Patient ID: {{ $patient->patient_id }}</p>
                </div>
                <div>
                    <a href="{{ route('receptionist.patients.show', $patient) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Patient
                    </a>
                    <a href="{{ route('receptionist.billing.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary ms-2">
                        <i class="fas fa-plus me-1"></i> Create New Invoice
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
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

                <!-- Patient Summary Card -->
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-uppercase text-muted mb-2">Billing Summary</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Total Invoices:</span>
                                    <span class="font-weight-bold">{{ $billings->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Total Billed Amount:</span>
                                    <span class="font-weight-bold">₱ {{ number_format($billings->sum('amount_due'), 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Total Paid:</span>
                                    <span class="font-weight-bold text-success">₱ {{ number_format($billings->sum('amount_paid'), 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <span>Balance Due:</span>
                                    <span class="font-weight-bold text-danger">₱ {{ number_format($billings->sum('amount_due') - $billings->sum('amount_paid'), 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-uppercase text-muted mb-2">Payment Status</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-gradient-success mb-0">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Paid</p>
                                                            <h5 class="text-white font-weight-bolder mb-0">
                                                                {{ $billings->where('payment_status', 'Paid')->count() }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-white shadow text-center rounded-circle">
                                                            <i class="fas fa-check text-success"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-gradient-warning mb-0">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Partial</p>
                                                            <h5 class="text-white font-weight-bolder mb-0">
                                                                {{ $billings->where('payment_status', 'Partial')->count() }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-white shadow text-center rounded-circle">
                                                            <i class="fas fa-hourglass-half text-warning"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-gradient-danger mb-0">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold">Pending</p>
                                                            <h5 class="text-white font-weight-bolder mb-0">
                                                                {{ $billings->where('payment_status', 'Pending')->count() }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-white shadow text-center rounded-circle">
                                                            <i class="fas fa-clock text-danger"></i>
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

                <!-- Billing Records Table -->
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Service/Treatment</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                <th class="text-secondary opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($billings as $billing)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $billing->invoice_number }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                @if($billing->due_date && $billing->due_date < now() && $billing->payment_status != 'Paid')
                                                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Overdue</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $billing->description }}</p>
                                    @if($billing->treatment)
                                    <p class="text-xs text-secondary mb-0">
                                        Treatment: {{ $billing->treatment->name }}
                                    </p>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div>
                                        <p class="text-xs font-weight-bold mb-0">₱ {{ number_format($billing->amount_due, 2) }}</p>
                                        @if($billing->payment_status == 'Partial')
                                        <p class="text-xs text-secondary mb-0">
                                            Paid: ₱ {{ number_format($billing->amount_paid, 2) }}
                                        </p>
                                        <p class="text-xs text-danger mb-0">
                                            Balance: ₱ {{ number_format($billing->amount_due - $billing->amount_paid, 2) }}
                                        </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($billing->payment_status == 'Paid')
                                        <span class="badge badge-sm bg-gradient-success">Paid</span>
                                    @elseif($billing->payment_status == 'Partial')
                                        <span class="badge badge-sm bg-gradient-warning">Partial</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-danger">Pending</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        {{ $billing->invoice_date ? $billing->invoice_date->format('M d, Y') : 'N/A' }}
                                    </span>
                                    <p class="text-xs text-secondary mb-0">
                                        Due: {{ $billing->due_date ? $billing->due_date->format('M d, Y') : 'N/A' }}
                                    </p>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group">
                                        <a href="{{ route('receptionist.billing.show', $billing) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($billing->payment_status != 'Paid')
                                        <a href="{{ route('receptionist.billing.edit', $billing) }}" class="btn btn-sm btn-outline-primary ms-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('receptionist.billing.process-payment', $billing) }}" class="btn btn-sm btn-outline-success ms-1" title="Process Payment">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('receptionist.billing.print-invoice', $billing) }}" class="btn btn-sm btn-outline-secondary ms-1" title="Print Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No billing records found for this patient</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($billings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $billings->links() }}
                </div>
                @endif

                <!-- Unpaid Invoices Alert -->
                @if($billings->where('payment_status', '!=', 'Paid')->count() > 0)
                <div class="alert alert-warning mt-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading">Unpaid Invoices</h5>
                            <p class="mb-0">
                                This patient has {{ $billings->where('payment_status', '!=', 'Paid')->count() }} unpaid or partially paid 
                                invoice(s) with a total balance of 
                                ₱ {{ number_format($billings->where('payment_status', '!=', 'Paid')->sum('amount_due') - $billings->where('payment_status', '!=', 'Paid')->sum('amount_paid'), 2) }}.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection