@extends('layouts.dentist')

@section('title', 'My Treatments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Treatments</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('dentist.treatments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="service_id" class="form-label">Treatment Type</label>
                    <select name="service_id" id="service_id" class="form-select">
                        <option value="">All Treatment Types</option>
                        @foreach($dentalServices as $service)
                            <option value="{{ $service->service_id }}" {{ request('service_id') == $service->service_id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dentist.treatments.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ !request('status') ? 'active' : '' }}" href="{{ route('dentist.treatments.index') }}">All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request('status') == 'Planned' ? 'active' : '' }}" href="{{ route('dentist.treatments.index', ['status' => 'Planned']) }}">Planned</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request('status') == 'In Progress' ? 'active' : '' }}" href="{{ route('dentist.treatments.index', ['status' => 'In Progress']) }}">In Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request('status') == 'Completed' ? 'active' : '' }}" href="{{ route('dentist.treatments.index', ['status' => 'Completed']) }}">Completed</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($treatments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Treatment</th>
                                        <th>Tooth #</th>
                                        <th>Status</th>
                                        <th>Cost</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($treatments as $treatment)
                                        <tr>
                                            <td>{{ $treatment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('dentist.patients.show', $treatment->patient) }}">
                                                    {{ $treatment->patient->first_name }} {{ $treatment->patient->last_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $treatment->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($treatment->description, 30) }}</small>
                                            </td>
                                            <td>{{ $treatment->tooth_number ?? 'N/A' }}</td>
                                            <td>
                                                @if($treatment->status == 'Planned')
                                                    <span class="badge bg-info">{{ $treatment->status }}</span>
                                                @elseif($treatment->status == 'In Progress')
                                                    <span class="badge bg-warning">{{ $treatment->status }}</span>
                                                @elseif($treatment->status == 'Completed')
                                                    <span class="badge bg-success">{{ $treatment->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $treatment->status }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($treatment->cost, 2) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('dentist.treatments.show', $treatment) }}" class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>
                                                    @if(!$treatment->billings()->exists())
                                                        <a href="{{ route('dentist.treatments.edit', $treatment) }}" class="btn btn-sm btn-outline-secondary">
                                                            Edit
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $treatments->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                            <p class="mt-3">No treatments found matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Ensure tab navigation text is visible in both active and inactive states */
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: #212529;
        font-weight: 600;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        color: #0d6efd;
    }
</style>
@endpush