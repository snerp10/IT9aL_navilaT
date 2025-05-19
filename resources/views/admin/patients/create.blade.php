@extends('layouts.admin')
@section('title', 'Add Patient')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Add Patient</h1>
    <form method="POST" action="{{ route('patients.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Birth Date</label>
                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Emergency Contact Name</label>
                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Emergency Contact Number</label>
                <input type="text" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number') }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Blood Type</label>
                <input type="text" name="blood_type" class="form-control" value="{{ old('blood_type') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Allergies</label>
                <input type="text" name="allergies" class="form-control" value="{{ old('allergies') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Medical History</label>
                <input type="text" name="medical_history" class="form-control" value="{{ old('medical_history') }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Current Medications</label>
                <input type="text" name="current_medications" class="form-control" value="{{ old('current_medications') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Insurance Provider</label>
                <input type="text" name="insurance_provider" class="form-control" value="{{ old('insurance_provider') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Insurance Policy Number</label>
                <input type="text" name="insurance_policy_number" class="form-control" value="{{ old('insurance_policy_number') }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Create User Account?</label>
                <input type="checkbox" name="create_user_account" value="1">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Create Patient</button>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
