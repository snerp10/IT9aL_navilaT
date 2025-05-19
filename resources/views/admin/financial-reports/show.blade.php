@extends('layouts.admin')

@section('title', 'Financial Report Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Financial Report Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Financial Reports</a></li>
        <li class="breadcrumb-item active">Report #{{ $financialReport->report_id }}</li>
    </ol>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Report Header & Actions -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-file-earmark-bar-graph me-1"></i>
                Financial Report: {{ $financialReport->formatted_date }}
            </div>
            <div>
                <a href="{{ route('financial-reports.download-pdf', $financialReport->report_id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-pdf"></i> Download PDF
                </a>
                <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#deleteReportModal">
                    <i class="bi bi-trash"></i> Delete Report
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 40%">Report ID:</th>
                            <td>{{ $financialReport->report_id }}</td>
                        </tr>
                        <tr>
                            <th>Report Date:</th>
                            <td>{{ $financialReport->formatted_date }}</td>
                        </tr>
                        <tr>
                            <th>Generated On:</th>
                            <td>{{ $financialReport->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-4">Financial Summary</h5>
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-muted mb-1">Total Revenue</p>
                                    <h4 class="mb-0">₱{{ number_format($financialReport->total_revenue, 2) }}</h4>
                                </div>
                                <div class="col">
                                    <p class="text-muted mb-1">Total Expenses</p>
                                    <h4 class="mb-0">₱{{ number_format($financialReport->total_expenses, 2) }}</h4>
                                </div>
                                <div class="col">
                                    <p class="text-muted mb-1">Net Profit</p>
                                    <h4 class="mb-0 {{ $financialReport->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        ₱{{ number_format($financialReport->net_profit, 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Income Breakdown -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-graph-up me-1"></i>
                    Revenue Breakdown
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <i class="bi bi-circle-fill text-primary me-1"></i>
                                Services Revenue
                            </div>
                            <div class="fw-bold">₱{{ number_format($financialReport->revenue_from_services, 2) }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-circle-fill text-success me-1"></i>
                                Product Cost
                            </div>
                            <div class="fw-bold">₱{{ number_format($financialReport->total_product_cost, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-1"></i>
                    Profit Margin Analysis
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="profitMarginChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <i class="bi bi-circle-fill text-success me-1"></i>
                                Revenue
                            </div>
                            <div class="fw-bold">₱{{ number_format($financialReport->total_revenue, 2) }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-circle-fill text-danger me-1"></i>
                                Expenses
                            </div>
                            <div class="fw-bold">₱{{ number_format($financialReport->total_expenses, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Treatments Data Section - Keep if data exists in controller -->
    @if(isset($treatments) && $treatments->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-clipboard2-pulse me-1"></i>
            Treatments & Services Revenue
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Treatment</th>
                            <th>Patient</th>
                            <th>Dentist</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($treatments as $billing)
                            <tr>
                                <td>{{ $billing->created_at->format('M d, Y') }}</td>
                                <td>{{ $billing->treatment->name ?? 'Unknown Treatment' }}</td>
                                <td>{{ $billing->patient->name ?? 'Unknown Patient' }}</td>
                                <td>{{ $billing->treatment->dentist->name ?? 'Unknown Dentist' }}</td>
                                <td class="text-end">₱{{ number_format($billing->amount_due, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">Total Services Revenue:</td>
                            <td class="text-end">₱{{ number_format($financialReport->revenue_from_services, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-clipboard2-pulse me-1"></i>
            Treatments & Services Revenue
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                No treatment revenue details available for this report.
            </div>
        </div>
    </div>
    @endif
    
    <!-- Products Data Section - Keep if data exists in controller -->
    @if(isset($products) && $products->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-box-seam me-1"></i>
            Products Revenue
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $billing)
                            <tr>
                                <td>{{ $billing->created_at->format('M d, Y') }}</td>
                                <td>{{ $billing->product->product_name ?? 'Unknown Product' }}</td>
                                <td>{{ $billing->product->category->category_name ?? 'Unknown Category' }}</td>
                                <td>{{ $billing->quantity ?? 1 }}</td>
                                <td class="text-end">₱{{ number_format($billing->amount_due, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">Total Product Revenue:</td>
                            <td class="text-end">₱{{ number_format($financialReport->total_product_cost, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-box-seam me-1"></i>
            Products Revenue
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                No product revenue details available for this report.
            </div>
        </div>
    </div>
    @endif
    
    <!-- Expenses Data Section - Keep if data exists in controller -->
    @if(isset($expenses) && $expenses->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-cash-coin me-1"></i>
            Expenses
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Vendor/Supplier</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->date->format('M d, Y') }}</td>
                                <td>{{ $expense->description }}</td>
                                <td>{{ $expense->category }}</td>
                                <td>{{ $expense->vendor }}</td>
                                <td class="text-end">₱{{ number_format($expense->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">Total Expenses:</td>
                            <td class="text-end">₱{{ number_format($financialReport->total_expenses, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-cash-coin me-1"></i>
            Expenses
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                No expense details available for this report.
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Report Modal -->
<div class="modal fade" id="deleteReportModal" tabindex="-1" aria-labelledby="deleteReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteReportModalLabel">Delete Financial Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this financial report? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('financial-reports.destroy', $financialReport->report_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Breakdown Chart
        const servicesRevenue = {{ $financialReport->revenue_from_services ?? 0 }};
        const productCost = {{ $financialReport->total_product_cost ?? 0 }};
        
        new Chart(document.getElementById('revenueChart'), {
            type: 'pie',
            data: {
                labels: ['Services Revenue', 'Product Cost'],
                datasets: [{
                    data: [servicesRevenue, productCost],
                    backgroundColor: ['#0d6efd', '#198754'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Profit Margin Chart
        const totalRevenue = {{ $financialReport->total_revenue ?? 0 }};
        const totalExpenses = {{ $financialReport->total_expenses ?? 0 }};
        
        new Chart(document.getElementById('profitMarginChart'), {
            type: 'doughnut',
            data: {
                labels: ['Revenue', 'Expenses'],
                datasets: [{
                    data: [totalRevenue, totalExpenses],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '50%'
            }
        });
    });
</script>
@endsection
@endsection
