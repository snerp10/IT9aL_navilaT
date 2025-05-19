@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Employee Details</h1>
        <div>
            <a href="{{ route('employees.edit', $employee->employee_id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('payroll.employee-history', $employee->employee_id) }}" class="btn btn-primary">
                <i class="bi bi-cash-stack"></i> Payroll History
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3 bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0,4)] }} rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 3rem;">
                        <span>{{ substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1) }}</span>
                    </div>
                    
                    <h3 class="card-title mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    <p class="text-muted mb-2">{{ $employee->role }}</p>
                    
                    <span class="badge bg-{{ $employee->employment_status == 'Active' ? 'success' : ($employee->employment_status == 'Inactive' ? 'secondary' : 'warning') }} mb-3">
                        {{ $employee->employment_status }}
                    </span>
                    
                    @if($employee->specialization)
                    <p class="mb-1"><span class="fw-bold">Specialization:</span> {{ $employee->specialization }}</p>
                    @endif
                    
                    <hr>
                    
                    <div class="text-start">
                        <p class="mb-1"><i class="bi bi-envelope me-2"></i> {{ $employee->email }}</p>
                        <p class="mb-1"><i class="bi bi-telephone me-2"></i> {{ $employee->contact_number }}</p>
                        <p class="mb-1"><i class="bi bi-calendar-date me-2"></i> Joined {{ date('M d, Y', strtotime($employee->hire_date)) }}</p>
                        <p class="mb-3"><i class="bi bi-geo-alt me-2"></i> {{ $employee->address ?? 'No address on file' }}</p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal">
                            <i class="bi bi-trash"></i> Delete Employee
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('payroll.create') }}?employee_id={{ $employee->employee_id }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-cash-coin me-2"></i> Create Payroll
                    </a>
                    <a href="{{ route('appointments.index') }}?employee_id={{ $employee->employee_id }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-calendar-check me-2"></i> View Appointments
                    </a>
                    @if(!$employee->user_id)
                    <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus me-2"></i> Create User Account
                    </button>
                    @endif
                    <a href="#" class="list-group-item list-group-item-action" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Print Details
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Full Name</th>
                                    <td>{{ $employee->first_name }} {{ $employee->middle_name ? $employee->middle_name . ' ' : '' }}{{ $employee->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Employee ID</th>
                                    <td>{{ $employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td>{{ $employee->date_of_birth ? date('M d, Y', strtotime($employee->date_of_birth)) : 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ $employee->gender ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $employee->address ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Employment Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Position</th>
                                    <td>{{ $employee->role }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $employee->employment_status == 'Active' ? 'success' : ($employee->employment_status == 'Inactive' ? 'secondary' : 'warning') }}">
                                            {{ $employee->employment_status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hire Date</th>
                                    <td>{{ date('M d, Y', strtotime($employee->hire_date)) }}</td>
                                </tr>
                                <tr>
                                    <th>Employment Duration</th>
                                    <td>{{ \Carbon\Carbon::parse($employee->hire_date)->diffForHumans(null, true) }}</td>
                                </tr>
                                @if($employee->role == 'Dentist')
                                <tr>
                                    <th>Specialization</th>
                                    <td>{{ $employee->specialization ?? 'General Dentist' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Payroll Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Base Salary</th>
                                    <td>₱{{ number_format($employee->salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Frequency</th>
                                    <td>{{ $employee->payment_frequency ?? 'Monthly' }}</td>
                                </tr>
                                <tr>
                                    <th>Bank Account</th>
                                    <td>{{ $employee->bank_account ? '••••' . substr($employee->bank_account, -4) : 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Recent Payments</th>
                                    <td>
                                        @if(isset($recentPayrolls) && $recentPayrolls->count() > 0)
                                            {{ $recentPayrolls->count() }} in the last 3 months
                                        @else
                                            No recent payments
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="{{ route('payroll.employee-history', $employee->employee_id) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-cash-stack me-2"></i> View Full Payroll History
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">User Account</h5>
                        </div>
                        <div class="card-body">
                            @if($employee->user_id && $employee->user)
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    This employee has a user account in the system
                                </div>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Email</th>
                                        <td>{{ $employee->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td>{{ $employee->user->role }}</td>
                                    </tr>
                                </table>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No user account linked to this employee
                                </div>
                                <p>This employee doesn't have access to the system. You can create a user account to grant system access.</p>
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                    <i class="bi bi-person-plus me-2"></i> Create User Account
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($employee->notes)
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Notes</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $employee->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Employee Modal -->
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEmployeeModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone. All payroll records associated with this employee will also be removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('employees.destroy', $employee->employee_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
@if(!$employee->user_id)
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Create User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employees.create-user-account', $employee->employee_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You're creating a user account for <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>.</p>
                    <p>This will allow them to log into the system with the role: <strong>{{ $employee->role }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" value="{{ $employee->email }}" disabled>
                        <div class="form-text">This email will be used as the username for login</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    @media print {
        .container {
            width: 100%;
            max-width: 100%;
        }
        .btn, .modal, .list-group-item-action, .card-footer {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
