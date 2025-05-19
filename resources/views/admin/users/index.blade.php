@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <h1 class="mb-4">User Management</h1>
    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by email or ID" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                <option value="Admin" @if(request('role')=='Admin') selected @endif>Admin</option>
                <option value="Dentist" @if(request('role')=='Dentist') selected @endif>Dentist</option>
                <option value="Receptionist" @if(request('role')=='Receptionist') selected @endif>Receptionist</option>
                <option value="Patient" @if(request('role')=='Patient') selected @endif>Patient</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $users->appends(request()->input())->links('vendor.pagination.custom-theme') }}
    </div>
@endsection
