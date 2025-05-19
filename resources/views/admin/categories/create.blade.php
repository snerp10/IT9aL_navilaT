@extends('layouts.admin')
@section('title', 'Add Category')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Add New Category</h1>
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <!-- Add category fields here -->
        <button type="submit" class="btn btn-success">Create Category</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
