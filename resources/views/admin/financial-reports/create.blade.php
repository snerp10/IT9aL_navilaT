@extends('layouts.admin')

@section('title', 'Generate Financial Report')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Generate Financial Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Financial Reports</a></li>
        <li class="breadcrumb-item active">Generate New Report</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-file-earmark-plus me-1"></i>
            Report Parameters
        </div>
        <div class="card-body">
            <form action="{{ route('financial-reports.generate') }}" method="POST">
                @csrf
                
                <div class="mb-3 row">
                    <label for="report_type" class="col-md-3 col-form-label">Report Type</label>
                    <div class="col-md-9">
                        <select id="report_type" name="report_type" class="form-select @error('report_type') is-invalid @enderror" required>
                            <option value="daily">Daily Report</option>
                            <option value="monthly" selected>Monthly Report</option>
                            <option value="annual">Annual Report</option>
                            <option value="custom">Custom Date Range</option>
                        </select>
                        @error('report_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="start_date" class="col-md-3 col-form-label">Start Date</label>
                    <div class="col-md-9">
                        <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', now()->format('Y-m-01')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row" id="end_date_group">
                    <label for="end_date" class="col-md-3 col-form-label">End Date</label>
                    <div class="col-md-9">
                        <input type="date" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">
                <h5>Include in Report</h5>

                <div class="mb-3 row">
                    <div class="col-md-9 offset-md-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="include_treatments" name="include_treatments" value="1" checked>
                            <label class="form-check-label" for="include_treatments">Treatments & Services</label>
                        </div>
                        
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="include_products" name="include_products" value="1" checked>
                            <label class="form-check-label" for="include_products">Products & Inventory</label>
                        </div>
                        
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="include_expenses" name="include_expenses" value="1" checked>
                            <label class="form-check-label" for="include_expenses">Expenses & Costs</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportTypeSelect = document.getElementById('report_type');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const endDateGroup = document.getElementById('end_date_group');
        
        reportTypeSelect.addEventListener('change', function() {
            const today = new Date();
            const year = today.getFullYear();
            const month = (today.getMonth() + 1).toString().padStart(2, '0');
            const day = today.getDate().toString().padStart(2, '0');
            
            switch(this.value) {
                case 'daily':
                    startDateInput.value = `${year}-${month}-${day}`;
                    endDateInput.value = `${year}-${month}-${day}`;
                    endDateGroup.style.display = 'none';
                    break;
                    
                case 'monthly':
                    startDateInput.value = `${year}-${month}-01`;
                    endDateInput.value = new Date(year, today.getMonth() + 1, 0).toISOString().split('T')[0];
                    endDateGroup.style.display = 'none';
                    break;
                    
                case 'annual':
                    startDateInput.value = `${year}-01-01`;
                    endDateInput.value = `${year}-12-31`;
                    endDateGroup.style.display = 'none';
                    break;
                    
                case 'custom':
                    endDateGroup.style.display = 'flex';
                    break;
            }
        });
        
        // Initial setting based on default value
        reportTypeSelect.dispatchEvent(new Event('change'));
    });
</script>
@endsection
@endsection
