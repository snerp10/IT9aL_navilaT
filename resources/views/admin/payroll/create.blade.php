@extends('layouts.admin')

@section('title', 'Create Payroll Record')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create Payroll Record</h1>
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Payroll
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('payroll.store') }}">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}" 
                                    data-salary="{{ $employee->salary }}"
                                    {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->role }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pay_date" class="form-label">Pay Date</label>
                        <input type="date" class="form-control @error('pay_date') is-invalid @enderror" id="pay_date" name="pay_date" value="{{ old('pay_date') ?? date('Y-m-d') }}" required>
                        @error('pay_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="salary_amount" class="form-label">Base Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" class="form-control @error('salary_amount') is-invalid @enderror" id="salary_amount" name="salary_amount" value="{{ old('salary_amount') }}" required>
                            @error('salary_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bonus" class="form-label">Bonus</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('bonus') is-invalid @enderror" id="bonus" name="bonus" value="{{ old('bonus') ?? '0.00' }}">
                            @error('bonus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Optional. Enter 0 if no bonus.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="deductions" class="form-label">Deductions</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('deductions') is-invalid @enderror" id="deductions" name="deductions" value="{{ old('deductions') ?? '0.00' }}">
                            @error('deductions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Optional. Enter 0 if no deductions.</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title">Net Salary Calculation</h5>
                                        <p class="card-text">Base Salary + Bonus - Deductions</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <h4 class="text-primary" id="netSalary">₱0.00</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light me-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Create Payroll Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employeeSelect = document.getElementById('employee_id');
        const salaryInput = document.getElementById('salary_amount');
        const bonusInput = document.getElementById('bonus');
        const deductionsInput = document.getElementById('deductions');
        const netSalaryDisplay = document.getElementById('netSalary');
        
        // Set initial salary when an employee is selected
        employeeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const salary = selectedOption.dataset.salary;
                salaryInput.value = salary || '0.00';
                calculateNetSalary();
            }
        });
        
        // Calculate net salary whenever an input changes
        [salaryInput, bonusInput, deductionsInput].forEach(input => {
            input.addEventListener('input', calculateNetSalary);
        });
        
        function calculateNetSalary() {
            const salary = parseFloat(salaryInput.value) || 0;
            const bonus = parseFloat(bonusInput.value) || 0;
            const deductions = parseFloat(deductionsInput.value) || 0;
            
            const netSalary = salary + bonus - deductions;
            netSalaryDisplay.textContent = '₱' + netSalary.toFixed(2);
        }
        
        // Initial calculation
        calculateNetSalary();
    });
</script>
@endpush
@endsection
