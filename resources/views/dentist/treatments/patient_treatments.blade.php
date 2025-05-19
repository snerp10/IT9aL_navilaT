@extends('layouts.dentist')

@section('title', 'Patient Treatments - ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $patient->first_name }} {{ $patient->last_name }}'s Treatments</h1>
            <p class="text-muted">Patient ID: {{ $patient->patient_id }}</p>
        </div>
        <div>
            <a href="{{ route('dentist.patients.show', $patient) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-person me-1"></i> Patient Profile
            </a>
            <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> New Treatment
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link text-dark active" href="#treatments" data-bs-toggle="tab">All Treatments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#completed" data-bs-toggle="tab">Completed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#ongoing" data-bs-toggle="tab">Ongoing</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="treatments">
                            @if($treatments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
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
                                    <p class="mt-3">No treatments found for this patient.</p>
                                    <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Create First Treatment
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="completed">
                            @if($treatments->where('status', 'Completed')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Treatment</th>
                                                <th>Tooth #</th>
                                                <th>Cost</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($treatments->where('status', 'Completed') as $treatment)
                                                <tr>
                                                    <td>{{ $treatment->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <strong>{{ $treatment->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($treatment->description, 30) }}</small>
                                                    </td>
                                                    <td>{{ $treatment->tooth_number ?? 'N/A' }}</td>
                                                    <td>${{ number_format($treatment->cost, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('dentist.treatments.show', $treatment) }}" class="btn btn-sm btn-outline-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-clipboard-check fs-1 text-muted"></i>
                                    <p class="mt-3">No completed treatments found for this patient.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="ongoing">
                            @if($treatments->whereIn('status', ['Planned', 'In Progress'])->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Treatment</th>
                                                <th>Tooth #</th>
                                                <th>Status</th>
                                                <th>Cost</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($treatments->whereIn('status', ['Planned', 'In Progress']) as $treatment)
                                                <tr>
                                                    <td>{{ $treatment->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <strong>{{ $treatment->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($treatment->description, 30) }}</small>
                                                    </td>
                                                    <td>{{ $treatment->tooth_number ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($treatment->status == 'Planned')
                                                            <span class="badge bg-info">{{ $treatment->status }}</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ $treatment->status }}</span>
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
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-clipboard fs-1 text-muted"></i>
                                    <p class="mt-3">No ongoing treatments found for this patient.</p>
                                </div>
                            @endif
                        </div>
                    </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tabs
    const triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'))
    triggerTabList.forEach(function (triggerEl) {
        new bootstrap.Tab(triggerEl)
    });
});
</script>
@endpush