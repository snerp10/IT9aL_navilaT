@extends('layouts.receptionist')

@section('title', 'Edit Patient')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Patient: {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                <div>
                    <a href="{{ route('receptionist.patients.show', $patient) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Patient
                    </a>
                </div>
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

                <form action="{{ route('receptionist.patients.update', $patient) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $patient->middle_name) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $patient->birth_date) }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $patient->address) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="active" {{ old('status', $patient->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $patient->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
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
                                                <option value="A+" {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="O+" {{ old('blood_type', $patient->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_type', $patient->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                                <option value="AB+" {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="allergies" class="form-label">Allergies</label>
                                        <textarea class="form-control" id="allergies" name="allergies" rows="2">{{ old('allergies', $patient->allergies) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="medical_history" class="form-label">Medical History</label>
                                        <textarea class="form-control" id="medical_history" name="medical_history" rows="3">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="current_medications" class="form-label">Current Medications</label>
                                        <textarea class="form-control" id="current_medications" name="current_medications" rows="2">{{ old('current_medications', $patient->current_medications) }}</textarea>
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
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', $patient->contact_number) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $patient->email) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number', $patient->emergency_contact_number) }}" required>
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
                                        <input type="text" class="form-control" id="insurance_provider" name="insurance_provider" value="{{ old('insurance_provider', $patient->insurance_provider) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="insurance_policy_number" class="form-label">Policy Number</label>
                                        <input type="text" class="form-control" id="insurance_policy_number" name="insurance_policy_number" value="{{ old('insurance_policy_number', $patient->insurance_policy_number) }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes', $patient->notes) }}</textarea>
                                        <div class="form-text">Additional notes or comments about this patient.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Update Patient</button>
                        <a href="{{ route('receptionist.patients.show', $patient) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection