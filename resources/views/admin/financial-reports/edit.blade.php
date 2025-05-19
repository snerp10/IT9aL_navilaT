@extends('layouts.admin')
@section('title', 'Edit Financial Report')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Financial Report</h1>
    <form method="POST" action="{{ route('financial-reports.update', $report->report_id ?? 1) }}">
        @csrf
        @method('PUT')
        <!-- Add financial report fields here -->
        <button type="submit" class="btn btn-primary">Update Report</button>
        <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
