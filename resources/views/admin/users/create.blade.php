@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
    <h1 class="mb-4">Add New User</h1>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">User Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" id="role-select" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Dentist">Dentist</option>
                            <option value="Receptionist">Receptionist</option>
                            <option value="Patient">Patient</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> After creating this user account, you will be directed to complete the user profile based on the selected role.
        </div>

        <button type="submit" class="btn btn-success">Create User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
