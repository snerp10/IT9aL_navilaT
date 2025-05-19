@extends('layouts.admin')

@section('title', 'Treatment History for ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Treatment History</h1>
                <div>
                    <a href="{{ route('patients.show', $patient->patient_id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Back to Patient
                    </a>
                    <a href="{{ route('treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Treatment
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                            <p class="text-muted small mb-0">
                                Patient ID: {{ $patient->patient_id }} | 
                                DOB: {{ $patient->date_of_birth ? date('M d, Y', strtotime($patient->date_of_birth)) : 'N/A' }} | 
                                Phone: {{ $patient->phone }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <ul class="nav nav-pills" id="treatmentViewTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-view" type="button" role="tab" aria-selected="true">
                                        <i class="bi bi-list-ul"></i> List View
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline-view" type="button" role="tab" aria-selected="false">
                                        <i class="bi bi-calendar-event"></i> Timeline
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="treatmentViewTabContent">
                        <!-- List View -->
                        <div class="tab-pane fade show active" id="list-view" role="tabpanel" aria-labelledby="list-tab">
                            @if(count($treatments) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Treatment</th>
                                                <th>Tooth #</th>
                                                <th>Dentist</th>
                                                <th>Cost</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($treatments as $treatment)
                                                <tr>
                                                    <td>{{ date('M d, Y', strtotime($treatment->created_at)) }}</td>
                                                    <td>{{ $treatment->treatment_name }}</td>
                                                    <td>{{ $treatment->tooth_number ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($treatment->appointment && $treatment->appointment->dentist)
                                                            Dr. {{ $treatment->appointment->dentist->first_name }} {{ $treatment->appointment->dentist->last_name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($treatment->cost, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ 
                                                            $treatment->status === 'Completed' ? 'success' : 
                                                            ($treatment->status === 'In Progress' ? 'warning' : 'info') 
                                                        }}">
                                                            {{ $treatment->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('treatments.show', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('treatments.edit', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> No treatment records found for this patient.
                                </div>
                            @endif
                        </div>

                        <!-- Timeline View -->
                        <div class="tab-pane fade" id="timeline-view" role="tabpanel" aria-labelledby="timeline-tab">
                            @if(count($treatments) > 0)
                                <div class="timeline">
                                    @foreach($treatments as $treatment)
                                        <div class="timeline-item">
                                            <div class="row mb-4">
                                                <div class="col-md-3 col-lg-2">
                                                    <div class="timeline-date">
                                                        <span class="badge bg-light text-dark shadow-sm p-2">
                                                            {{ date('M d, Y', strtotime($treatment->created_at)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-9 col-lg-10">
                                                    <div class="card shadow-sm border-{{ 
                                                        $treatment->status === 'Completed' ? 'success' : 
                                                        ($treatment->status === 'In Progress' ? 'warning' : 'info')
                                                    }}">
                                                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                                            <h5 class="mb-0">{{ $treatment->treatment_name }}</h5>
                                                            <span class="badge bg-{{ 
                                                                $treatment->status === 'Completed' ? 'success' : 
                                                                ($treatment->status === 'In Progress' ? 'warning' : 'info') 
                                                            }}">
                                                                {{ $treatment->status }}
                                                            </span>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-4">
                                                                    <p class="mb-1 fw-bold text-muted small">Tooth Number</p>
                                                                    <p>{{ $treatment->tooth_number ?? 'N/A' }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p class="mb-1 fw-bold text-muted small">Dentist</p>
                                                                    <p>
                                                                        @if($treatment->appointment && $treatment->appointment->dentist)
                                                                            Dr. {{ $treatment->appointment->dentist->first_name }} {{ $treatment->appointment->dentist->last_name }}
                                                                        @else
                                                                            N/A
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p class="mb-1 fw-bold text-muted small">Cost</p>
                                                                    <p>${{ number_format($treatment->cost, 2) }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <p class="mb-1 fw-bold text-muted small">Description</p>
                                                                <p>{{ $treatment->description }}</p>
                                                            </div>
                                                            @if($treatment->notes)
                                                                <div>
                                                                    <p class="mb-1 fw-bold text-muted small">Notes</p>
                                                                    <p>{{ $treatment->notes }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="card-footer bg-white">
                                                            <div class="btn-group">
                                                                <a href="{{ route('treatments.show', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="bi bi-eye"></i> Details
                                                                </a>
                                                                <a href="{{ route('treatments.edit', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-pencil"></i> Edit
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> No treatment records found for this patient.
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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const tabLinks = document.querySelectorAll('#treatmentViewTab button');
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener('click', function(event) {
            event.preventDefault();
            const tabLink = event.currentTarget;
            
            // Remove active class from all tabs
            tabLinks.forEach(link => {
                link.classList.remove('active');
                link.setAttribute('aria-selected', 'false');
            });
            
            // Add active class to clicked tab
            tabLink.classList.add('active');
            tabLink.setAttribute('aria-selected', 'true');
            
            // Show corresponding content
            const targetId = tabLink.getAttribute('data-bs-target');
            const tabContents = document.querySelectorAll('#treatmentViewTabContent .tab-pane');
            tabContents.forEach(content => {
                content.classList.remove('show', 'active');
            });
            
            document.querySelector(targetId).classList.add('show', 'active');
        });
    });
});
</script>
@endsection