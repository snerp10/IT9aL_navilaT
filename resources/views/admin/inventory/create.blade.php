@extends('layouts.admin')
@section('title', 'Add Inventory')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Add New Inventory Entry</h1>
    <form method="POST" action="{{ route('inventory.store') }}">
        @csrf
        <!-- Add inventory fields here -->
        <button type="submit" class="btn btn-success">Create Inventory</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
