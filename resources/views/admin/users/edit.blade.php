@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <h1 class="mb-4">Edit User</h1>
    <form method="POST" action="{{ route('users.update', $user->user_id) }}">
        @csrf
        @method('PUT')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">User Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" id="role-select" class="form-select" required>
                            <option value="Admin" @if($user->role=='Admin') selected @endif>Admin</option>
                            <option value="Dentist" @if($user->role=='Dentist') selected @endif>Dentist</option>
                            <option value="Receptionist" @if($user->role=='Receptionist') selected @endif>Receptionist</option>
                            <option value="Patient" @if($user->role=='Patient') selected @endif>Patient</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Password <small>(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" @if($user->is_active) selected @endif>Active</option>
                            <option value="0" @if(!$user->is_active) selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information (Common to both Patient and Employee) -->
        <div class="card mb-4" id="personal-info-card" style="{{ in_array($user->role, ['Admin', 'Dentist', 'Receptionist', 'Patient']) ? '' : 'display: none;' }}">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $user->patient->first_name ?? $user->employee->first_name ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ $user->patient->middle_name ?? $user->employee->middle_name ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $user->patient->last_name ?? $user->employee->last_name ?? '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select Gender</option>
                            <option value="Male" @if(($user->patient->gender ?? $user->employee->gender ?? '') == 'Male') selected @endif>Male</option>
                            <option value="Female" @if(($user->patient->gender ?? $user->employee->gender ?? '') == 'Female') selected @endif>Female</option>
                            <option value="Other" @if(($user->patient->gender ?? $user->employee->gender ?? '') == 'Other') selected @endif>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Birth Date</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ $user->patient->birth_date ?? $user->employee->birth_date ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ $user->patient->contact_number ?? $user->employee->contact_number ?? '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ $user->patient->address ?? $user->employee->address ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient-specific Information -->
        <div class="card mb-4" id="patient-info-card" style="{{ $user->role == 'Patient' ? '' : 'display: none;' }}">
            <div class="card-header">
                <h5 class="mb-0">Patient Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ $user->patient->emergency_contact_name ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emergency Contact Number</label>
                        <input type="text" name="emergency_contact_number" class="form-control" value="{{ $user->patient->emergency_contact_number ?? '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Blood Type</label>
                        <select name="blood_type" class="form-select">
                            <option value="">Select Blood Type</option>
                            <option value="A+" @if(($user->patient->blood_type ?? '') == 'A+') selected @endif>A+</option>
                            <option value="A-" @if(($user->patient->blood_type ?? '') == 'A-') selected @endif>A-</option>
                            <option value="B+" @if(($user->patient->blood_type ?? '') == 'B+') selected @endif>B+</option>
                            <option value="B-" @if(($user->patient->blood_type ?? '') == 'B-') selected @endif>B-</option>
                            <option value="AB+" @if(($user->patient->blood_type ?? '') == 'AB+') selected @endif>AB+</option>
                            <option value="AB-" @if(($user->patient->blood_type ?? '') == 'AB-') selected @endif>AB-</option>
                            <option value="O+" @if(($user->patient->blood_type ?? '') == 'O+') selected @endif>O+</option>
                            <option value="O-" @if(($user->patient->blood_type ?? '') == 'O-') selected @endif>O-</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2">{{ $user->patient->allergies ?? '' }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control" rows="3">{{ $user->patient->medical_history ?? '' }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Current Medications</label>
                        <textarea name="current_medications" class="form-control" rows="2">{{ $user->patient->current_medications ?? '' }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Insurance Provider</label>
                        <input type="text" name="insurance_provider" class="form-control" value="{{ $user->patient->insurance_provider ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Insurance Policy Number</label>
                        <input type="text" name="insurance_policy_number" class="form-control" value="{{ $user->patient->insurance_policy_number ?? '' }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee-specific Information -->
        <div class="card mb-4" id="employee-info-card" style="{{ in_array($user->role, ['Admin', 'Dentist', 'Receptionist']) ? '' : 'display: none;' }}">
            <div class="card-header">
                <h5 class="mb-0">Employee Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="{{ $user->employee->specialization ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" name="years_of_experience" class="form-control" value="{{ $user->employee->years_of_experience ?? '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Education</label>
                        <textarea name="education" class="form-control" rows="2">{{ $user->employee->education ?? '' }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Certifications</label>
                        <textarea name="certifications" class="form-control" rows="2">{{ $user->employee->certifications ?? '' }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Salary</label>
                        <input type="number" name="salary" class="form-control" step="0.01" value="{{ $user->employee->salary ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hire Date</label>
                        <input type="date" name="hire_date" class="form-control" value="{{ $user->employee->hire_date ?? date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employment Status</label>
                        <select name="employment_status" class="form-select">
                            <option value="Active" @if(($user->employee->employment_status ?? '') == 'Active') selected @endif>Active</option>
                            <option value="On Leave" @if(($user->employee->employment_status ?? '') == 'On Leave') selected @endif>On Leave</option>
                            <option value="Probation" @if(($user->employee->employment_status ?? '') == 'Probation') selected @endif>Probation</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role-select');
            const patientInfoCard = document.getElementById('patient-info-card');
            const employeeInfoCard = document.getElementById('employee-info-card');
            const personalInfoCard = document.getElementById('personal-info-card');

            roleSelect.addEventListener('change', function() {
                const selectedRole = this.value;
                
                // Hide all role-specific cards first
                patientInfoCard.style.display = 'none';
                employeeInfoCard.style.display = 'none';
                personalInfoCard.style.display = 'none';
                
                if (selectedRole === 'Patient') {
                    patientInfoCard.style.display = 'block';
                    personalInfoCard.style.display = 'block';
                } else if (['Admin', 'Dentist', 'Receptionist'].includes(selectedRole)) {
                    employeeInfoCard.style.display = 'block';
                    personalInfoCard.style.display = 'block';
                }
            });
        });
    </script>
    @endpush
@endsection
