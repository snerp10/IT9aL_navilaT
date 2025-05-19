@extends('layouts.admin')

@section('title', 'Annual Financial Summary')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Annual Financial Summary</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Financial Reports</a></li>
        <li class="breadcrumb-item active">Annual Summary</li>
    </ol>

    <!-- Year Selector -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-calendar-year me-1"></i>
            Select Year
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('financial-reports.annual-summary') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="year" class="form-label">Year</label>
                    <select id="year" name="year" class="form-select">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">
                        <i class="bi bi-search me-1"></i> View Annual Summary
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Annual Summary Cards -->
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
                    <span class="small text-white">Full Year {{ $summary['year'] }}</span>
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
            Monthly Revenue & Expenses for {{ $summary['year'] }}
        </div>
        <div class="card-body">
            <div style="height:400px;">
                <canvas id="annualFinancialChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Trend Analysis Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-graph-up me-1"></i>
            Revenue & Profit Trends for {{ $summary['year'] }}
        </div>
        <div class="card-body">
            <div style="height:300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-table me-1"></i>
            Monthly Revenue Breakdown
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Treatment Revenue</th>
                            <th>Product Revenue</th>
                            <th>Total Revenue</th>
                            <th>Expenses</th>
                            <th>Net Profit</th>
                            <th>Profit Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyBreakdown as $month)
                            <tr>
                                <td>{{ $month['month_name'] }}</td>
                                <td class="text-end">₱{{ number_format($month['treatment_revenue'], 2) }}</td>
                                <td class="text-end">₱{{ number_format($month['product_revenue'], 2) }}</td>
                                <td class="text-end">₱{{ number_format($month['total_revenue'], 2) }}</td>
                                <td class="text-end">₱{{ number_format($month['expenses'], 2) }}</td>
                                <td class="text-end 
                                    {{ $month['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₱{{ number_format($month['net_profit'], 2) }}
                                </td>
                                <td class="text-end">
                                    {{ number_format(($month['total_revenue'] > 0 ? $month['net_profit'] / $month['total_revenue'] * 100 : 0), 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td class="text-end">Annual Total:</td>
                            <td class="text-end">₱{{ number_format($summary['treatment_revenue'], 2) }}</td>
                            <td class="text-end">₱{{ number_format($summary['product_revenue'], 2) }}</td>
                            <td class="text-end">₱{{ number_format($summary['total_revenue'], 2) }}</td>
                            <td class="text-end">₱{{ number_format($summary['total_expenses'], 2) }}</td>
                            <td class="text-end 
                                {{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ₱{{ number_format($summary['net_profit'], 2) }}
                            </td>
                            <td class="text-end">
                                {{ number_format(($summary['total_revenue'] > 0 ? $summary['net_profit'] / $summary['total_revenue'] * 100 : 0), 1) }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('financial-reports.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
            
            <form action="{{ route('financial-reports.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="report_type" value="annual">
                <input type="hidden" name="start_date" value="{{ $year }}-01-01">
                <input type="hidden" name="end_date" value="{{ $year }}-12-31">
                <input type="hidden" name="include_treatments" value="1">
                <input type="hidden" name="include_products" value="1">
                <input type="hidden" name="include_expenses" value="1">
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-earmark-bar-graph"></i> Save as Report
                </button>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded');
            return;
        }
        const monthlyData = @json($monthlyBreakdown ?? []);
        if (!Array.isArray(monthlyData) || monthlyData.length === 0) {
            document.getElementById('annualFinancialChart').parentElement.innerHTML = '<div class="text-center text-muted">No data available for this year.</div>';
            document.getElementById('trendChart').parentElement.innerHTML = '<div class="text-center text-muted">No trend data available for this year.</div>';
            return;
        }
        const months = monthlyData.map(month => month.month_name);
        const revenueData = monthlyData.map(month => month.total_revenue);
        const expensesData = monthlyData.map(month => month.expenses);
        const profitData = monthlyData.map(month => month.net_profit);
        const treatmentRevenueData = monthlyData.map(month => month.treatment_revenue);
        const productRevenueData = monthlyData.map(month => month.product_revenue);
        // Bar Chart - Revenue & Expenses
        new Chart(document.getElementById('annualFinancialChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Treatment Revenue',
                        data: treatmentRevenueData,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Product Revenue',
                        data: productRevenueData,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: expensesData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: false,
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        stacked: false,
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
        // Line Chart - Trends
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Total Revenue',
                        data: revenueData,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Net Profit',
                        data: profitData,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
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