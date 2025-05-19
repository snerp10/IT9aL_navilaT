@extends('layouts.admin')
@section('title', 'Category Details')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Category Details</h1>
    <div class="card">
        <div class="card-body">
            <!-- Add category details here -->
            <a href="{{ route('categories.edit', $category->category_id ?? 1) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
