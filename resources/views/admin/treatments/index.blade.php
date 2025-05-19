@extends('layouts.admin')

@section('title', 'Treatments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Treatment Management</h1>
        <div>
            <a href="{{ route('treatments.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New Treatment
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('treatments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search treatments..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Planned" {{ request('status') == 'Planned' ? 'selected' : '' }}>Planned</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('treatments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Treatment</th>
                            <th>Date</th>
                            <th>Dentist</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                            <tr>
                                <td>
                                    <a href="{{ route('patients.show', $treatment->patient_id) }}" class="d-flex align-items-center text-decoration-none">
                                        <div class="avatar me-2 bg-light text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <span>{{ substr($treatment->patient->first_name ?? 'U', 0, 1) }}{{ substr($treatment->patient->last_name ?? 'P', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            {{ $treatment->patient->first_name ?? 'Unknown' }} {{ $treatment->patient->last_name ?? 'Patient' }}
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('treatments.show', $treatment->treatment_id) }}">
                                        {{ $treatment->treatment_name }}
                                    </a>
                                    @if($treatment->tooth_number)
                                        <span class="badge bg-light text-dark">Tooth #{{ $treatment->tooth_number }}</span>
                                    @endif
                                </td>
                                <td>{{ $treatment->appointment->appointment_date->format('M d, Y') }}</td>
                                <td>Dr. {{ $treatment->appointment->dentist->first_name ?? 'Unknown' }} {{ $treatment->appointment->dentist->last_name ?? '' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $treatment->status === 'Completed' ? 'success' : 
                                        ($treatment->status === 'In Progress' ? 'primary' : 'warning') 
                                    }}">{{ $treatment->status }}</span>
                                </td>
                                <td>${{ number_format($treatment->cost, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('treatments.show', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('treatments.edit', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="if(confirm('Are you sure you want to delete this treatment?')) { 
                                            document.getElementById('delete-treatment-{{ $treatment->treatment_id }}').submit(); 
                                        }">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-treatment-{{ $treatment->treatment_id }}" action="{{ route('treatments.destroy', $treatment->treatment_id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
                                        <h5>No treatments found</h5>
                                        <p class="text-muted">No treatment records match your criteria.</p>
                                        <a href="{{ route('treatments.create') }}" class="btn btn-primary mt-2">Add New Treatment</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($treatments->count() > 0)
            <div class="card-footer bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        @if(method_exists($treatments, 'firstItem'))
                            <p class="mb-0">Showing {{ $treatments->firstItem() }} to {{ $treatments->lastItem() }} of {{ $treatments->total() }} treatments</p>
                        @else
                            <p class="mb-0">Showing {{ $treatments->count() }} treatments</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end">
                            @if(method_exists($treatments, 'links'))
                                {{ $treatments->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Treatment Statistics Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-clipboard-plus fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Planned</h6>
                            <h3 class="mb-0">{{ $treatments->where('status', 'Planned')->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('treatments.index', ['status' => 'Planned']) }}" class="btn btn-sm btn-outline-primary w-100">View Planned</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-hourglass-split fs-4 text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">In Progress</h6>
                            <h3 class="mb-0">{{ $treatments->where('status', 'In Progress')->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('treatments.index', ['status' => 'In Progress']) }}" class="btn btn-sm btn-outline-info w-100">View In Progress</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-clipboard-check fs-4 text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Completed</h6>
                            <h3 class="mb-0">{{ $treatments->where('status', 'Completed')->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('treatments.index', ['status' => 'Completed']) }}" class="btn btn-sm btn-outline-success w-100">View Completed</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-dark bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-cash-stack fs-4 text-dark"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Total Revenue</h6>
                            <h3 class="mb-0">${{ number_format($treatments->sum('cost'), 2) }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('financial-reports.monthly-summary') }}" class="btn btn-sm btn-outline-dark w-100">View Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection