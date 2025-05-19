@extends('layouts.dentist')

@section('title', 'Patient Treatments')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Treatment History</h1>
        <div>
            <a href="{{ route('dentist.patients.show', $patient) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Patient
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Patient Info Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fs-3">{{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                            <p class="text-muted mb-0">
                                {{ $patient->gender }}, {{ \Carbon\Carbon::parse($patient->birth_date)->age }} years
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-telephone me-2"></i>Contact Number</p>
                        <p class="mb-0">{{ $patient->contact_number }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-1"><i class="bi bi-envelope me-2"></i>Email</p>
                        <p class="mb-0">{{ $patient->email }}</p>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('dentist.patients.dental-chart', $patient) }}" class="btn btn-outline-primary">
                            <i class="bi bi-grid-3x3"></i> View Dental Chart
                        </a>
                        <a href="{{ route('dentist.patients.appointments', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-calendar-check"></i> Appointment History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Treatment Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-2">Total Treatments</h6>
                                    <h2 class="mb-0">{{ $treatments->total() }}</h2>
                                </div>
                                <i class="bi bi-clipboard2-plus fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-2">Completed</h6>
                                    <h2 class="mb-0">{{ $treatments->where('status', 'Completed')->count() }}</h2>
                                </div>
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-2">Planned/In Progress</h6>
                                    <h2 class="mb-0">{{ $treatments->whereIn('status', ['Planned', 'In Progress'])->count() }}</h2>
                                </div>
                                <i class="bi bi-hourglass-split fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatments List -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Treatment List</h5>
                    </div>
                </div>
                <div class="card-body">
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
                                                <small class="text-muted">{{ Str::limit($treatment->description, 50) }}</small>
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
                            <p class="mt-3">No treatments have been recorded for this patient.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection