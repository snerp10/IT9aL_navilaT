@extends('layouts.dentist')

@section('title', 'Patient Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dentist.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentist.patients.index') }}">Patients</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $patient->first_name }} {{ $patient->last_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Patient Profile Summary -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center position-relative pt-5">
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; border: 4px solid white;">
                            <span>{{ substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <h4 class="mt-4">{{ $patient->first_name }} {{ $patient->middle_name ? $patient->middle_name . ' ' : '' }}{{ $patient->last_name }}</h4>
                    <p class="text-muted mb-2">Patient ID: {{ $patient->patient_id }}</p>
                    
                    @if($patient->birth_date)
                        <div class="badge bg-light text-dark mb-3">
                            {{ \Carbon\Carbon::parse($patient->birth_date)->age }} years old
                            ({{ \Carbon\Carbon::parse($patient->birth_date)->format('M d, Y') }})
                        </div>
                    @endif
                    
                    <hr>
                    
                    <div class="d-flex justify-content-around mb-3">
                        <a href="{{ route('dentist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-calendar-plus me-1"></i> New Appointment
                        </a>
                        <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-clipboard2-plus me-1"></i> New Treatment
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-person-lines-fill text-primary me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Phone Number</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <span>{{ $patient->contact_number ?? 'Not provided' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Email Address</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            <span>{{ $patient->email ?? 'Not provided' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Home Address</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            <span>{{ $patient->address ?? 'Not provided' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <small class="text-muted d-block">Emergency Contact</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-heart me-2 text-primary"></i>
                            <div>
                                <span>{{ $patient->emergency_contact_name ?? 'Not provided' }}</span>
                                @if($patient->emergency_contact_number)
                                    <small class="d-block text-muted">{{ $patient->emergency_contact_number }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Medical Information Summary -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-clipboard2-heart text-danger me-2"></i>Medical Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded bg-light">
                                <small class="text-muted d-block">Blood Type</small>
                                <span class="fs-5 fw-medium">{{ $patient->blood_type ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-light">
                                <small class="text-muted d-block">Gender</small>
                                <span class="fs-5 fw-medium">{{ $patient->gender ?? 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted d-block mb-1">Allergies</small>
                        @if($patient->allergies)
                            <p class="mb-3">{{ $patient->allergies }}</p>
                        @else
                            <p class="text-muted mb-3">No allergies recorded</p>
                        @endif
                        
                        <small class="text-muted d-block mb-1">Current Medications</small>
                        @if($patient->current_medications)
                            <p class="mb-3">{{ $patient->current_medications }}</p>
                        @else
                            <p class="text-muted mb-3">No medications recorded</p>
                        @endif
                        
                        <small class="text-muted d-block mb-1">Medical History</small>
                        @if($patient->medical_history)
                            <p class="mb-0">{{ $patient->medical_history }}</p>
                        @else
                            <p class="text-muted mb-0">No medical history recorded</p>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('dentist.patients.dental-chart', $patient->patient_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-grid-3x3 me-1"></i> View Dental Chart
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Patient Data Tabs -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="patientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="treatments-tab" data-bs-toggle="tab" data-bs-target="#treatments" type="button" role="tab" aria-controls="treatments" aria-selected="true">
                                <i class="bi bi-clipboard2-pulse me-1"></i> Treatment History
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="false">
                                <i class="bi bi-calendar2-check me-1"></i> Appointments
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab" aria-controls="notes" aria-selected="false">
                                <i class="bi bi-journal-text me-1"></i> Clinical Notes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="false">
                                <i class="bi bi-receipt me-1"></i> Billing
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="patientTabsContent">
                        <!-- Treatments Tab -->
                        <div class="tab-pane fade show active" id="treatments" role="tabpanel" aria-labelledby="treatments-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Treatment History</h5>
                                <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-lg me-1"></i> Add Treatment
                                </a>
                            </div>
                            
                            @if(isset($treatments) && $treatments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Treatment</th>
                                                <th>Tooth</th>
                                                <th>Notes</th>
                                                <th>Cost</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($treatments as $treatment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($treatment->created_at)->format('M d, Y') }}</td>
                                                    <td>{{ $treatment->name }}</td>
                                                    <td>{{ $treatment->tooth_number ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $treatment->description }}">
                                                            {{ \Illuminate\Support\Str::limit($treatment->description, 30) }}
                                                        </span>
                                                    </td>
                                                    <td>₱{{ number_format($treatment->cost, 2) }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('dentist.treatments.show', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('images/no-data.svg') }}" alt="No treatments" class="img-fluid mb-3" style="max-height: 150px;">
                                    <h6 class="text-muted">No treatment records found</h6>
                                    <p class="text-muted small mb-3">This patient doesn't have any treatment records yet.</p>
                                    <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i> Create Treatment Record
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Appointments Tab -->
                        <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Appointment History</h5>
                                <a href="{{ route('dentist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Schedule Appointment
                                </a>
                            </div>
                            
                            @if(isset($appointments) && $appointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y - h:i A') }}</td>
                                                    <td>{{ $appointment->service ?? 'General Checkup' }}</td>
                                                    <td>
                                                        <span class="badge rounded-pill bg-{{ $appointment->status == 'Completed' ? 'success' : ($appointment->status == 'Scheduled' ? 'info' : ($appointment->status == 'In Progress' ? 'primary' : 'warning')) }}">
                                                            {{ $appointment->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $appointment->notes }}">
                                                            {{ \Illuminate\Support\Str::limit($appointment->notes, 30) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="btn-group">
                                                            <a href="{{ route('dentist.appointments.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('dentist.treatments.create', ['appointment_id' => $appointment->appointment_id]) }}" class="btn btn-sm btn-outline-success">
                                                                <i class="bi bi-clipboard-plus"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('images/no-data.svg') }}" alt="No appointments" class="img-fluid mb-3" style="max-height: 150px;">
                                    <h6 class="text-muted">No appointment records found</h6>
                                    <p class="text-muted small mb-3">This patient doesn't have any appointment records yet.</p>
                                    <a href="{{ route('dentist.appointments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i> Schedule an Appointment
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Clinical Notes Tab -->
                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Clinical Notes</h5>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                    <i class="bi bi-plus-lg me-1"></i> Add Note
                                </button>
                            </div>
                            
                            @if(isset($clinicalNotes) && $clinicalNotes->count() > 0)
                                <div class="timeline">
                                    @foreach($clinicalNotes as $note)
                                        <div class="timeline-item">
                                            <div class="timeline-point"></div>
                                            <div class="timeline-content">
                                                <div class="card border-0 shadow-sm mb-3">
                                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                                        <span class="text-muted small">
                                                            <i class="bi bi-calendar3 me-1"></i> 
                                                            {{ \Carbon\Carbon::parse($note->created_at)->format('M d, Y - h:i A') }}
                                                        </span>
                                                        <span class="badge bg-primary">{{ $note->category ?? 'General' }}</span>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="mb-0">{{ $note->content }}</p>
                                                    </div>
                                                    <div class="card-footer bg-white text-muted small">
                                                        <i class="bi bi-person-circle me-1"></i> 
                                                        Added by: Dr. {{ $note->created_by_name ?? 'Unknown' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('images/no-data.svg') }}" alt="No notes" class="img-fluid mb-3" style="max-height: 150px;">
                                    <h6 class="text-muted">No clinical notes found</h6>
                                    <p class="text-muted small mb-3">There are no clinical notes for this patient yet.</p>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                        <i class="bi bi-plus-circle me-1"></i> Add Clinical Note
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Billing Tab -->
                        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Billing History</h5>
                                <a href="{{ route('dentist.billings.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-lg me-1"></i> Create Invoice
                                </a>
                            </div>
                            
                            @if(isset($billings) && $billings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($billings as $billing)
                                                <tr>
                                                    <td>{{ $billing->invoice_number }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('M d, Y') }}</td>
                                                    <td>₱{{ number_format($billing->total_amount, 2) }}</td>
                                                    <td>
                                                        <span class="badge rounded-pill bg-{{ $billing->payment_status == 'Paid' ? 'success' : ($billing->payment_status == 'Partial' ? 'warning' : 'danger') }}">
                                                            {{ $billing->payment_status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('dentist.billings.show', $billing->billing_id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('images/no-data.svg') }}" alt="No billing records" class="img-fluid mb-3" style="max-height: 150px;">
                                    <h6 class="text-muted">No billing records found</h6>
                                    <p class="text-muted small mb-3">This patient doesn't have any billing records yet.</p>
                                    <a href="{{ route('dentist.billings.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-plus-circle me-1"></i> Create Invoice
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Clinical Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Add Clinical Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dentist.patients.add-note', $patient->patient_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="note_category" class="form-label">Category</label>
                        <select class="form-select" id="note_category" name="category" required>
                            <option value="General">General</option>
                            <option value="Diagnosis">Diagnosis</option>
                            <option value="Treatment Plan">Treatment Plan</option>
                            <option value="Medication">Medication</option>
                            <option value="Follow-up">Follow-up</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="note_content" class="form-label">Note Content</label>
                        <textarea class="form-control" id="note_content" name="content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-point {
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #0d6efd;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-content {
        padding-bottom: 0.5rem;
    }
    
    .avatar {
        font-weight: 600;
    }
</style>
@endpush