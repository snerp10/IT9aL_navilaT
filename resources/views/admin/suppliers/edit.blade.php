@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Supplier</h1>
    <form method="POST" action="{{ route('suppliers.update', $supplier->supplier_id ?? 1) }}">
        @csrf
        @method('PUT')
        <!-- Add supplier fields here -->
        <button type="submit" class="btn btn-primary">Update Supplier</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
