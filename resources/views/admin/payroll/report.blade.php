@extends('layouts.admin')

@section('title', 'Payroll Report')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Payroll Report</h1>
        <div>
            <button onclick="window.print()" class="btn btn-outline-dark">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Back to Payroll
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <form method="GET" action="{{ route('payroll.report') }}" class="row g-2">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', date('Y-m-t')) }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Generate Report</button>
                    <a href="{{ route('payroll.report') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="mb-4 text-center">
                <h4 class="mb-1">Payroll Summary Report</h4>
                <p class="text-muted">
                    Period: {{ date('F d, Y', strtotime(request('start_date', date('Y-m-01')))) }} to {{ date('F d, Y', strtotime(request('end_date', date('Y-m-t')))) }}
                </p>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 text-center mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Total Payroll Records</h6>
                            <h2 class="mb-0 text-primary">{{ $payrolls->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Total Salary</h6>
                            <h2 class="mb-0 text-primary">₱{{ number_format($totalSalary ?? $payrolls->sum('salary_amount'), 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Total Bonuses</h6>
                            <h2 class="mb-0 text-success">₱{{ number_format($totalBonus ?? $payrolls->sum('bonus'), 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body py-3">
                            <h6 class="text-muted">Total Deductions</h6>
                            <h2 class="mb-0 text-danger">₱{{ number_format($totalDeductions ?? $payrolls->sum('deductions'), 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-success mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Total Net Salary Paid</h5>
                        <p class="mb-0 small">Total Base Salary + Total Bonuses - Total Deductions</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h3 class="mb-0">₱{{ number_format($totalNetSalary ?? ($payrolls->sum('salary_amount') + $payrolls->sum('bonus') - $payrolls->sum('deductions')), 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Department Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Payroll Composition</h5>
                </div>
                <div class="card-body">
                    <canvas id="payrollCompositionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payroll Details</h5>
            <a href="{{ route('payroll.index') }}?start_date={{ request('start_date', date('Y-m-01')) }}&end_date={{ request('end_date', date('Y-m-t')) }}" class="btn btn-sm btn-outline-primary">View All Records</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Role</th>
                            <th>Pay Date</th>
                            <th>Salary</th>
                            <th>Bonus</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->payroll_id }}</td>
                                <td>
                                    <a href="{{ route('employees.show', $payroll->employee->employee_id ?? 0) }}">
                                        {{ $payroll->employee->first_name ?? 'Unknown' }} {{ $payroll->employee->last_name ?? 'Employee' }}
                                    </a>
                                </td>
                                <td>{{ $payroll->employee->role ?? 'N/A' }}</td>
                                <td>{{ date('M d, Y', strtotime($payroll->pay_date)) }}</td>
                                <td>₱{{ number_format($payroll->salary_amount, 2) }}</td>
                                <td>₱{{ number_format($payroll->bonus, 2) }}</td>
                                <td>₱{{ number_format($payroll->deductions, 2) }}</td>
                                <td class="fw-bold">₱{{ number_format($payroll->salary_amount + $payroll->bonus - $payroll->deductions, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-cash-stack fs-1 d-block mb-3"></i>
                                        No payroll records found for the selected period
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($payrolls->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Totals:</td>
                            <td class="fw-bold">₱{{ number_format($payrolls->sum('salary_amount'), 2) }}</td>
                            <td class="fw-bold">₱{{ number_format($payrolls->sum('bonus'), 2) }}</td>
                            <td class="fw-bold">₱{{ number_format($payrolls->sum('deductions'), 2) }}</td>
                            <td class="fw-bold">₱{{ number_format($payrolls->sum(function($payroll) {
                                return $payroll->salary_amount + $payroll->bonus - $payroll->deductions;
                            }), 2) }}</td>
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

    <div class="text-center mt-5 text-muted">
        <p class="mb-1">Report Generated: {{ now()->format('F d, Y h:i A') }}</p>
        <p>Generated by: {{ auth()->user()->name ?? 'Administrator' }}</p>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample department data - in a real application, this would be calculated on the server
        const departmentData = {
            labels: ['Dentist', 'Admin', 'Receptionist', 'Assistant'],
            datasets: [{
                data: [
                    {{ $payrolls->filter(function($p) { return optional($p->employee)->role === 'Dentist'; })->sum('salary_amount') }},
                    {{ $payrolls->filter(function($p) { return optional($p->employee)->role === 'Admin'; })->sum('salary_amount') }},
                    {{ $payrolls->filter(function($p) { return optional($p->employee)->role === 'Receptionist'; })->sum('salary_amount') }},
                    {{ $payrolls->filter(function($p) { return optional($p->employee)->role === 'Assistant'; })->sum('salary_amount') }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)'
                ]
            }]
        };

        // Payroll composition data
        const compositionData = {
            labels: ['Base Salary', 'Bonuses', 'Deductions'],
            datasets: [{
                data: [
                    {{ $payrolls->sum('salary_amount') }},
                    {{ $payrolls->sum('bonus') }},
                    {{ $payrolls->sum('deductions') }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ]
            }]
        };

        // Department distribution chart
        new Chart(
            document.getElementById('departmentChart').getContext('2d'),
            {
                type: 'pie',
                data: departmentData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Payroll Distribution by Department'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ₱${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            }
        );
        
        // Payroll composition chart
        new Chart(
            document.getElementById('payrollCompositionChart').getContext('2d'),
            {
                type: 'doughnut',
                data: compositionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Payroll Cost Breakdown'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ₱${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            }
        );
    });
</script>
@endpush

@push('styles')
<style>
    @media print {
        body {
            padding: 0;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 100%;
        }
        .no-print, .no-print * {
            display: none !important;
        }
        .card {
            border: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        table {
            width: 100% !important;
        }
    }
</style>
@endpush
@endsection