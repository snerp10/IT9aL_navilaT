@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
        <a href="{{ route('employees.show', $employee->employee_id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Details
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('employees.update', $employee->employee_id) }}">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $employee->birth_date ? $employee->birth_date->format('Y-m-d') : '') }}" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $employee->contact_number) }}" required>
                        @error('contact_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $employee->address) }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2 mb-3">Employment Information</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="Admin" {{ old('role', $employee->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Dentist" {{ old('role', $employee->role) == 'Dentist' ? 'selected' : '' }}>Dentist</option>
                            <option value="Receptionist" {{ old('role', $employee->role) == 'Receptionist' ? 'selected' : '' }}>Receptionist</option>
                            <option value="Assistant" {{ old('role', $employee->role) == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="employment_status" class="form-label">Employment Status</label>
                        <select class="form-select @error('employment_status') is-invalid @enderror" id="employment_status" name="employment_status" required>
                            <option value="Active" {{ old('employment_status', $employee->employment_status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('employment_status', $employee->employment_status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="On Leave" {{ old('employment_status', $employee->employment_status) == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hire_date" class="form-label">Hire Date</label>
                        <input type="date" class="form-control @error('hire_date') is-invalid @enderror" id="hire_date" name="hire_date" value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}" required>
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4 dentist-field" style="display: {{ old('role', $employee->role) == 'Dentist' ? 'flex' : 'none' }};">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2 mb-3">Dentist Information</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="specialization" name="specialization" value="{{ old('specialization', $employee->specialization) }}">
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="years_of_experience" class="form-label">Years of Experience</label>
                        <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $employee->years_of_experience) }}">
                        @error('years_of_experience')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="education" class="form-label">Education</label>
                        <input type="text" class="form-control @error('education') is-invalid @enderror" id="education" name="education" value="{{ old('education', $employee->education) }}">
                        @error('education')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="certifications" class="form-label">Certifications</label>
                        <textarea class="form-control @error('certifications') is-invalid @enderror" id="certifications" name="certifications" rows="2">{{ old('certifications', $employee->certifications) }}</textarea>
                        @error('certifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2 mb-3">Compensation</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}" required>
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                @if($employee->user_id)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2 mb-3">User Account Information</h5>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            This employee has a user account with the system. Email changes will also affect the login credentials.
                        </div>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('employees.show', $employee->employee_id) }}" class="btn btn-light">Cancel</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const dentistFields = document.querySelectorAll('.dentist-field');
        
        function toggleDentistFields() {
            dentistFields.forEach(field => {
                field.style.display = roleSelect.value === 'Dentist' ? 'flex' : 'none';
            });
        }
        
        // Toggle on change
        roleSelect.addEventListener('change', toggleDentistFields);
    });
</script>
@endpush
@endsection
