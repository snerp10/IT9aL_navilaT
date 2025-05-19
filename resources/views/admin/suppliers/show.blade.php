@extends('layouts.admin')
@section('title', 'Supplier Details')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Supplier Details</h1>
    <div class="card">
        <div class="card-body">
            <!-- Add supplier details here -->
            <a href="{{ route('suppliers.edit', $supplier->supplier_id ?? 1) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
