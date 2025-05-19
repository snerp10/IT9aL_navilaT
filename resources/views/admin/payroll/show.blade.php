@extends('layouts.admin')

@section('title', 'Payroll Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Payroll Details</h1>
        <div>
            <a href="{{ route('payroll.edit', $payroll->payroll_id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Employee Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar mx-auto mb-3 bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0,4)] }} rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            <span>{{ substr($payroll->employee->first_name ?? 'U', 0, 1) . substr($payroll->employee->last_name ?? 'U', 0, 1) }}</span>
                        </div>
                        <h4 class="mb-1">{{ $payroll->employee->first_name ?? 'Unknown' }} {{ $payroll->employee->last_name ?? 'Employee' }}</h4>
                        <p class="text-muted mb-0">{{ $payroll->employee->role ?? 'Unknown Role' }}</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="text-muted mb-0 small">Employee ID</p>
                        <p class="fw-bold">{{ $payroll->employee->employee_id ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-0 small">Email</p>
                        <p class="fw-bold">{{ $payroll->employee->email ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-0 small">Status</p>
                        <p>
                            <span class="badge bg-{{ $payroll->employee->employment_status == 'Active' ? 'success' : ($payroll->employee->employment_status == 'Inactive' ? 'secondary' : 'warning') }}">
                                {{ $payroll->employee->employment_status ?? 'Unknown' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small">Hire Date</p>
                        <p class="fw-bold">{{ $payroll->employee->hire_date ? date('M d, Y', strtotime($payroll->employee->hire_date)) : 'N/A' }}</p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('employees.show', $payroll->employee->employee_id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-person"></i> View Employee Profile
                        </a>
                        <a href="{{ route('payroll.employee-history', $payroll->employee->employee_id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-clock-history"></i> View Payroll History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Payroll Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-0 small">Payroll ID</p>
                            <p class="fw-bold">{{ $payroll->payroll_id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-0 small">Pay Date</p>
                            <p class="fw-bold">{{ date('F d, Y', strtotime($payroll->pay_date)) }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <p class="text-muted mb-0 small">Base Salary</p>
                                    <h4 class="text-primary mb-0">₱{{ number_format($payroll->salary_amount, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <p class="text-muted mb-0 small">Payment Frequency</p>
                                    <h4 class="text-primary mb-0">{{ $payroll->employee->payment_frequency ?? 'Monthly' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <p class="text-muted mb-0 small">Bonus</p>
                                    <h4 class="text-success mb-0">₱{{ number_format($payroll->bonus, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <p class="text-muted mb-0 small">Deductions</p>
                                    <h4 class="text-danger mb-0">₱{{ number_format($payroll->deductions, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-success">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="alert-heading mb-0">Net Salary</h5>
                                        <p class="mb-0 small">Base Salary + Bonus - Deductions</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <h3 class="mb-0">₱{{ number_format($payroll->salary_amount + $payroll->bonus - $payroll->deductions, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($payroll->notes)
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted mb-1 small">Notes</p>
                            <p class="p-3 bg-light rounded">{{ $payroll->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Record Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-0 small">Created By</p>
                            <p class="fw-bold">{{ $payroll->created_by ?? 'System' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-0 small">Created At</p>
                            <p class="fw-bold">{{ $payroll->created_at ? $payroll->created_at->format('F d, Y g:i A') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-0 small">Last Updated By</p>
                            <p class="fw-bold">{{ $payroll->updated_by ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-0 small">Last Updated At</p>
                            <p class="fw-bold">{{ $payroll->updated_at ? $payroll->updated_at->format('F d, Y g:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <form action="{{ route('payroll.destroy', $payroll->payroll_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payroll record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete Record
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
