@extends('layouts.admin')
@section('title', 'Edit Inventory')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Inventory</h1>
    <form method="POST" action="{{ route('inventory.update', $inventory->inventory_id ?? 1) }}">
        @csrf
        @method('PUT')
        <!-- Add inventory fields here -->
        <button type="submit" class="btn btn-primary">Update Inventory</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
