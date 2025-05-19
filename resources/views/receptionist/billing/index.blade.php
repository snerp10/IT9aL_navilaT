@extends('layouts.receptionist')

@section('title', 'Billing Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Billing Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('receptionist.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Billing Management</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-file-invoice me-1"></i>
                Billings
            </div>
            <div>
                <a href="{{ route('receptionist.billing.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create New Billing
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <form action="{{ route('receptionist.billing.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Payment Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('receptionist.billing.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Amount Due</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billings as $billing)
                        <tr>
                            <td>{{ $billing->invoice_number }}</td>
                            <td>
                                @if($billing->patient)
                                    <a href="{{ route('receptionist.billing.patient', $billing->patient->patient_id) }}">
                                        {{ $billing->patient->last_name }}, {{ $billing->patient->first_name }}
                                    </a>
                                @else
                                    Unknown Patient
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($billing->invoice_date)->format('M d, Y') }}</td>
                            <td>₱{{ number_format($billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0), 2) }}</td>
                            <td>₱{{ number_format($billing->amount_paid ?? 0, 2) }}</td>
                            <td>
                                @if($billing->payment_status == 'Paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($billing->payment_status == 'Partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-danger">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('receptionist.billing.show', $billing) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($billing->payment_status != 'Paid')
                                        <a href="{{ route('receptionist.billing.edit', $billing) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <a href="{{ route('receptionist.billing.process-payment', $billing) }}" class="btn btn-sm btn-success" title="Process Payment">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('receptionist.billing.print-invoice', $billing) }}" class="btn btn-sm btn-secondary" title="Print Invoice" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    
                                    @if($billing->payment_status != 'Paid')
                                        <form action="{{ route('receptionist.billing.destroy', $billing) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this billing?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No billing records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $billings->appends(request()->query())->links() }}
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-pie me-1"></i>
                            Billing Status Summary
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <h5>Pending</h5>
                                    <div class="display-4 text-danger">
                                        {{ $billings->where('payment_status', 'Pending')->count() }}
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h5>Partial</h5>
                                    <div class="display-4 text-warning">
                                        {{ $billings->where('payment_status', 'Partial')->count() }}
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h5>Paid</h5>
                                    <div class="display-4 text-success">
                                        {{ $billings->where('payment_status', 'Paid')->count() }}
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