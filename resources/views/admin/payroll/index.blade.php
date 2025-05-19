@extends('layouts.admin')

@section('title', 'Payroll Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Payroll Management</h1>
        <div>
            <a href="{{ route('payroll.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Create Payroll Record
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row g-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('payroll.index') }}" class="row g-2">
                        <div class="col-md-4">
                            <input type="month" class="form-control" name="month" value="{{ request('month') ?? date('Y-m') }}" placeholder="Select Month">
                        </div>
                        <div class="col-md-4">
                            <select name="employee" class="form-select">
                                <option value="">All Employees</option>
                                @foreach($employees ?? [] as $employee)
                                <option value="{{ $employee->employee_id }}" {{ request('employee') == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                        <i class="bi bi-cash"></i> Generate Payroll
                    </button>
                    <a href="{{ route('payroll.report') }}" class="btn btn-outline-success ms-2">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Pay Date</th>
                            <th>Salary</th>
                            <th>Bonus</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls ?? [] as $payroll)
                            <tr>
                                <td>{{ $payroll->payroll_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0,4)] }} rounded-circle text-white">
                                            <span>{{ substr($payroll->employee->first_name ?? 'U', 0, 1) . substr($payroll->employee->last_name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $payroll->employee->first_name ?? '' }} {{ $payroll->employee->last_name ?? '' }}</div>
                                            <div class="small text-muted">{{ $payroll->employee->role ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $payroll->pay_date ? date('M d, Y', strtotime($payroll->pay_date)) : 'N/A' }}</td>
                                <td>₱{{ number_format($payroll->salary_amount, 2) }}</td>
                                <td>₱{{ number_format($payroll->bonus, 2) }}</td>
                                <td>₱{{ number_format($payroll->deductions, 2) }}</td>
                                <td class="fw-bold">₱{{ number_format($payroll->salary_amount + $payroll->bonus - $payroll->deductions, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('payroll.show', $payroll->payroll_id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('payroll.edit', $payroll->payroll_id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('payroll.destroy', $payroll->payroll_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payroll record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-cash-stack fs-1 d-block mb-3"></i>
                                        No payroll records found
                                    </div>
                                    <a href="{{ route('payroll.create') }}" class="btn btn-sm btn-primary mt-3">Create Payroll Record</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(isset($payrolls) && count($payrolls) > 0)
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Totals:</td>
                                <td class="fw-bold">₱{{ number_format($payrolls->sum('salary_amount'), 2) }}</td>
                                <td class="fw-bold">₱{{ number_format($payrolls->sum('bonus'), 2) }}</td>
                                <td class="fw-bold">₱{{ number_format($payrolls->sum('deductions'), 2) }}</td>
                                <td class="fw-bold">₱{{ number_format($payrolls->sum(function($payroll) {
                                    return $payroll->salary_amount + $payroll->bonus - $payroll->deductions;
                                }), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @if(isset($payrolls) && method_exists($payrolls, 'links'))
            <div class="card-footer bg-white">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-labelledby="generatePayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payroll.generate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="generatePayrollModalLabel">Generate Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This will generate payroll records for all active employees who don't already have a record for the selected pay date.</p>
                    <div class="mb-3">
                        <label for="pay_date" class="form-label">Pay Date</label>
                        <input type="date" class="form-control" id="pay_date" name="pay_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
