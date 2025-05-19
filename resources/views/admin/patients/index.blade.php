@extends('layouts.admin')

@section('title', 'Patient Management')

@section('content')
    <h1 class="mb-4">Patient Management</h1>
    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or contact" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="all">All Status</option>
                <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
        <div class="col-md-5 text-end">
            <a href="{{ route('patients.create') }}" class="btn btn-success">Add Patient</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->contact_number }}</td>
                        <td>{{ ucfirst($patient->status) }}</td>
                        <td>{{ $patient->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No patients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $patients->withQueryString()->links('vendor.pagination.custom-theme') }}
    </div>
@endsection
