@extends('layouts.receptionist')

@section('title', 'Create New Patient')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Patient</h5>
                    <a href="{{ route('receptionist.patients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Patients
                    </a>
                </div>
                <div class="card-body p-3">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('receptionist.patients.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Personal Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Medical Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="blood_type" class="form-label">Blood Type</label>
                                                <select class="form-control" id="blood_type" name="blood_type">
                                                    <option value="">Select Blood Type</option>
                                                    <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                                    <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                                    <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                                    <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                                    <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                                    <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                                    <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                    <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="allergies" class="form-label">Allergies</label>
                                            <textarea class="form-control" id="allergies" name="allergies" rows="2">{{ old('allergies') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="medical_history" class="form-label">Medical History</label>
                                            <textarea class="form-control" id="medical_history" name="medical_history" rows="3">{{ old('medical_history') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="current_medications" class="form-label">Current Medications</label>
                                            <textarea class="form-control" id="current_medications" name="current_medications" rows="2">{{ old('current_medications') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information & Insurance -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Contact Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emergency_contact_number" class="form-label">Emergency Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Insurance Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="insurance_provider" class="form-label">Insurance Provider</label>
                                            <input type="text" class="form-control" id="insurance_provider" name="insurance_provider" value="{{ old('insurance_provider') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="insurance_policy_number" class="form-label">Policy Number</label>
                                            <input type="text" class="form-control" id="insurance_policy_number" name="insurance_policy_number" value="{{ old('insurance_policy_number') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">User Account (Optional)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="create_user_account" name="create_user_account" value="1" {{ old('create_user_account') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="create_user_account">
                                                Create a user account for this patient
                                            </label>
                                            <div class="form-text">
                                                If checked, an account will be created with the email address, and a random password will be generated.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create Patient</button>
                            <a href="{{ route('receptionist.patients.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection