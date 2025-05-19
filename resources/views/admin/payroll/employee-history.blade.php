@extends('layouts.admin')

@section('title', 'Employee Payroll History')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Payroll History: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
        <div>
            <a href="{{ route('payroll.create') }}?employee_id={{ $employee->employee_id }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Create Payroll Record
            </a>
            <a href="{{ route('employees.show', $employee->employee_id) }}" class="btn btn-info ms-2">
                <i class="bi bi-person"></i> View Employee
            </a>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Back to Payroll
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Employee Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3 bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0,4)] }} rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <span>{{ substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                            <p class="text-muted mb-0">{{ $employee->role }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <p class="text-muted mb-0 small">Employee ID</p>
                            <p class="fw-bold">{{ $employee->employee_id }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p class="text-muted mb-0 small">Status</p>
                            <p>
                                <span class="badge bg-{{ $employee->employment_status == 'Active' ? 'success' : ($employee->employment_status == 'Inactive' ? 'secondary' : 'warning') }}">
                                    {{ $employee->employment_status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p class="text-muted mb-0 small">Base Salary</p>
                            <p class="fw-bold">₱{{ number_format($employee->salary, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p class="text-muted mb-0 small">Payment Frequency</p>
                            <p class="fw-bold">{{ $employee->payment_frequency ?? 'Monthly' }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p class="text-muted mb-0 small">Hire Date</p>
                            <p class="fw-bold">{{ $employee->hire_date ? date('M d, Y', strtotime($employee->hire_date)) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Payroll Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-muted">Total Payouts</h6>
                                <h3 class="text-primary mt-2">{{ $payrolls->count() }}</h3>
                                <p class="text-muted small">Records Found</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-muted">Year To Date</h6>
                                <h3 class="text-success mt-2">₱{{ number_format($payrolls->where('pay_date', '>=', date('Y-01-01'))->sum(function($payroll) {
                                    return $payroll->salary_amount + $payroll->bonus - $payroll->deductions;
                                }), 2) }}</h3>
                                <p class="text-muted small">Net Income</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-muted">Average Salary</h6>
                                <h3 class="text-info mt-2">₱{{ number_format($payrolls->avg('salary_amount'), 2) }}</h3>
                                <p class="text-muted small">Base Salary</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row g-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('payroll.employee-history', $employee->employee_id) }}" class="row g-2">
                        <div class="col-md-5">
                            <input type="month" class="form-control" name="month" value="{{ request('month') ?? date('Y-m') }}" placeholder="Select Month">
                        </div>
                        <div class="col-md-4">
                            <select name="year" class="form-select">
                                <option value="">All Years</option>
                                @foreach(range(date('Y'), date('Y') - 5) as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('payroll.create') }}?employee_id={{ $employee->employee_id }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Payroll
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
                            <th>Pay Date</th>
                            <th>Salary</th>
                            <th>Bonus</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->payroll_id }}</td>
                                <td>{{ date('M d, Y', strtotime($payroll->pay_date)) }}</td>
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
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-cash-stack fs-1 d-block mb-3"></i>
                                        No payroll records found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($payrolls->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Totals:</td>
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

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Monthly Salary Trend</h5>
        </div>
        <div class="card-body">
            <canvas id="salaryTrendChart" height="100"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample data - in a real application, this would be populated from the server
        const payrollData = @json($payrolls->sortBy('pay_date')->take(12)->map(function($payroll) {
            return [
                'date' => date('M Y', strtotime($payroll->pay_date)),
                'salary' => $payroll->salary_amount,
                'bonus' => $payroll->bonus,
                'deductions' => $payroll->deductions,
                'netSalary' => $payroll->salary_amount + $payroll->bonus - $payroll->deductions
            ];
        }));
        
        const ctx = document.getElementById('salaryTrendChart').getContext('2d');
        
        const labels = payrollData.map(item => item.date);
        const netSalaryData = payrollData.map(item => item.netSalary);
        const baseSalaryData = payrollData.map(item => item.salary);
        const bonusData = payrollData.map(item => item.bonus);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Net Salary',
                        data: netSalaryData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Base Salary',
                        data: baseSalaryData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Bonus',
                        data: bonusData,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (₱)'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection