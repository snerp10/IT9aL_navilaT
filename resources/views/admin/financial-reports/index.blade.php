@extends('layouts.admin')

@section('title', 'Financial Reports')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Financial Reports</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Financial Reports</li>
    </ol>
    
    <!-- Alert Messages -->
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
    
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Financial Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Monthly Revenue</div>
                            <div class="h2 mb-0">₱{{ number_format($monthlySummary['total_revenue'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-cash-coin fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('financial-reports.monthly-summary') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Monthly Profit</div>
                            <div class="h2 mb-0">₱{{ number_format($monthlySummary['net_profit'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-graph-up-arrow fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('financial-reports.monthly-summary') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Treatment Revenue</div>
                            <div class="h2 mb-0">₱{{ number_format($monthlySummary['treatment_revenue'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-clipboard2-pulse fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('financial-reports.monthly-summary') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h5">Product Revenue</div>
                            <div class="h2 mb-0">₱{{ number_format($monthlySummary['product_revenue'], 2) }}</div>
                        </div>
                        <div class="text-white">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('financial-reports.monthly-summary') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report Management -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-file-earmark-bar-graph me-1"></i>
                Financial Reports
            </div>
            <div>
                <a href="{{ route('financial-reports.annual-summary') }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-calendar-year"></i> Annual Summary
                </a>
                <a href="{{ route('financial-reports.monthly-summary') }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-calendar-month"></i> Monthly Summary
                </a>
                <a href="{{ route('financial-reports.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Generate New Report
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="financialReportsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Report Type</th>
                            <th>Period</th>
                            <th>Revenue</th>
                            <th>Expenses</th>
                            <th>Net Profit</th>
                            <th>Generated By</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->report_id }}</td>
                                <td>
                                    <span class="badge bg-info text-uppercase">{{ $report->report_type }}</span>
                                </td>
                                <td>{{ $report->report_period }}</td>
                                <td>₱{{ number_format($report->total_revenue, 2) }}</td>
                                <td>₱{{ number_format($report->total_expenses, 2) }}</td>
                                <td>
                                    <span class="{{ $report->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        ₱{{ number_format($report->net_profit, 2) }}
                                    </span>
                                </td>
                                <td>{{ $report->user->name ?? 'System' }}</td>
                                <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('financial-reports.show', $report->report_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('financial-reports.download-pdf', $report->report_id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReportModal" data-report-id="{{ $report->report_id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No financial reports found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $reports->links('vendor.pagination.custom-theme') }}
            </div>
        </div>
    </div>
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
                <p><strong>Report ID: </strong><span id="reportIdToDelete"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Fix: Use our custom POST delete route with empty action to be set by JavaScript -->
                <form id="deleteReportForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug - log to ensure script is running
        console.log('Financial report delete script loaded');
        
        const deleteReportModal = document.getElementById('deleteReportModal');
        if (deleteReportModal) {
            deleteReportModal.addEventListener('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                const button = event.relatedTarget;
                // Extract report ID from data attribute
                const reportId = button.getAttribute('data-report-id');
                console.log('Report ID to delete:', reportId);
                
                // Update the modal's content
                const reportIdSpan = document.getElementById('reportIdToDelete');
                if (reportIdSpan) {
                    reportIdSpan.textContent = reportId;
                } else {
                    console.error("Element #reportIdToDelete not found!");
                }
                
                // Fix: Use our custom POST delete route instead of the standard resource route
                const form = document.getElementById('deleteReportForm');
                if (form) {
                    // Using the custom delete route that we defined in web.php
                    form.action = '{{ url("/financial-reports") }}/' + reportId + '/delete';
                    console.log('Form action set to:', form.action);
                } else {
                    console.error("Element #deleteReportForm not found!");
                }
            });
        } else {
            console.error("Modal #deleteReportModal not found!");
        }
    });
</script>
@endsection
@endsection
