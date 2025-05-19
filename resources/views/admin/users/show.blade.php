@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
    <h1 class="mb-4">User Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">User ID: {{ $user->user_id }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Role:</strong> {{ $user->role }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $user->is_active ? 'Active' : 'Inactive' }}</p>
            <p class="card-text"><strong>Created:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@endsection
