@extends('layouts.admin')

@section('title', 'Monthly Financial Summary')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Monthly Financial Summary</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Financial Reports</a></li>
        <li class="breadcrumb-item active">Monthly Summary</li>
    </ol>

    <!-- Month & Year Selector -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-calendar-month me-1"></i>
            Select Month
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('financial-reports.monthly-summary') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="month" class="form-label">Month</label>
                    <select id="month" name="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i < 10 ? '0' . $i : $i }}" {{ $month == ($i < 10 ? '0' . $i : $i) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">Year</label>
                    <select id="year" name="year" class="form-select">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">
                        <i class="bi bi-search me-1"></i> View Summary
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Monthly Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Total Revenue</div>
                            <div class="h2 mb-0">₱{{ number_format($summary['total_revenue'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-cash-coin fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $summary['period'] }}</span>
                    <div class="small text-white"><i class="bi bi-arrow-up"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Net Profit</div>
                            <div class="h2 mb-0">₱{{ number_format($summary['net_profit'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-graph-up-arrow fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">
                        {{ number_format(($summary['total_revenue'] > 0 ? $summary['net_profit'] / $summary['total_revenue'] * 100 : 0), 1) }}% Profit Margin
                    </span>
                    <div class="small text-white"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Treatment Revenue</div>
                            <div class="h2 mb-0">₱{{ number_format($summary['treatment_revenue'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-clipboard2-pulse fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">
                        {{ number_format(($summary['total_revenue'] > 0 ? $summary['treatment_revenue'] / $summary['total_revenue'] * 100 : 0), 1) }}% of Revenue
                    </span>
                    <div class="small text-white"><i class="bi bi-clipboard-check"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Product Revenue</div>
                            <div class="h2 mb-0">₱{{ number_format($summary['product_revenue'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">
                        {{ number_format(($summary['total_revenue'] > 0 ? $summary['product_revenue'] / $summary['total_revenue'] * 100 : 0), 1) }}% of Revenue
                    </span>
                    <div class="small text-white"><i class="bi bi-box-arrow-up-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Expenses Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-bar-chart me-1"></i>
            Daily Revenue & Expenses for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
        </div>
        <div class="card-body">
            <div style="height:400px;">
                <canvas id="monthlyFinancialChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-table me-1"></i>
            Daily Revenue Breakdown
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Treatment Revenue</th>
                            <th>Product Revenue</th>
                            <th>Total Revenue</th>
                            <th>Expenses</th>
                            <th>Net Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyBreakdown as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day['date'])->format('M j') }}</td>
                                <td>{{ $day['day_name'] }}</td>
                                <td class="text-end">₱{{ number_format($day['treatment_revenue'], 2) }}</td>
                                <td class="text-end">₱{{ number_format($day['product_revenue'], 2) }}</td>
                                <td class="text-end">₱{{ number_format($day['total_revenue'], 2) }}</td>
                                <td class="text-end">₱{{ number_format($day['expenses'], 2) }}</td>
                                <td class="text-end 
                                    {{ $day['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₱{{ number_format($day['net_profit'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="2" class="text-end">Monthly Total:</td>
                            <td class="text-end">₱{{ number_format($summary['treatment_revenue'], 2) }}</td>
                            <td class="text-end">₱{{ number_format($summary['product_revenue'], 2) }}</td>
                            <td class="text-end">₱{{ number_format($summary['total_revenue'], 2) }}</td>
                            <td class="text-end">₱{{ number_format($summary['total_expenses'], 2) }}</td>
                            <td class="text-end 
                                {{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ₱{{ number_format($summary['net_profit'], 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('financial-reports.annual-summary', ['year' => $year]) }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-calendar-year"></i> View Annual Summary
        </a>
        
        <form action="{{ route('financial-reports.generate') }}" method="POST">
            @csrf
            <input type="hidden" name="report_type" value="monthly">
            <input type="hidden" name="start_date" value="{{ $year }}-{{ $month }}-01">
            <input type="hidden" name="end_date" value="{{ \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d') }}">
            <input type="hidden" name="include_treatments" value="1">
            <input type="hidden" name="include_products" value="1">
            <input type="hidden" name="include_expenses" value="1">
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-file-earmark-bar-graph"></i> Save as Report
            </button>
        </form>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Defensive: check if dailyBreakdown is not empty and Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded');
            return;
        }
        const dailyData = @json($dailyBreakdown ?? []);
        if (!Array.isArray(dailyData) || dailyData.length === 0) {
            document.getElementById('monthlyFinancialChart').parentElement.innerHTML = '<div class="text-center text-muted">No data available for this month.</div>';
            return;
        }
        const dates = dailyData.map(day => day.date);
        const revenueData = dailyData.map(day => day.total_revenue);
        const expensesData = dailyData.map(day => day.expenses);
        const profitData = dailyData.map(day => day.net_profit);
        new Chart(document.getElementById('monthlyFinancialChart'), {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Revenue',
                        data: revenueData,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: expensesData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Profit',
                        data: profitData,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1,
                        type: 'line',
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 90,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
@endsection