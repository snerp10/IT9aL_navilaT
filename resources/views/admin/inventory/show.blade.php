@extends('layouts.admin')
@section('title', 'Inventory Details')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Inventory Details</h1>
    <div class="card">
        <div class="card-body">
            <!-- Add inventory details here -->
            <a href="{{ route('inventory.edit', $inventory->inventory_id ?? 1) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
