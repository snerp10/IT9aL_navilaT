@extends('layouts.admin')
@section('title', 'Edit Category')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Category</h1>
    <form method="POST" action="{{ route('categories.update', $category->category_id ?? 1) }}">
        @csrf
        @method('PUT')
        <!-- Add category fields here -->
        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
