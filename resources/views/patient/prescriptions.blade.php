@extends('layouts.patient')

@section('title', 'My Prescriptions')

@section('content')
<div class="row fade-in">
    <!-- Info Card -->
    <div class="col-12 mb-4">
        <div class="card bg-light">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-primary text-white me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-capsule"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">My Prescriptions</h4>
                        <p class="mb-0">Track and manage your dental medication history. Contact your dentist for refill requests or questions about your medication.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search medications..." id="searchPrescription">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <select class="form-select" id="filterStatus">
                            <option value="all">All Prescriptions</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="refill">Needs Refill</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end mt-3 mt-md-0">
                        <button class="btn btn-outline-primary me-2" id="downloadPrescriptions">
                            <i class="bi bi-download me-1"></i> Download List
                        </button>
                        <button class="btn btn-outline-secondary" id="printPrescriptions">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Prescriptions -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Active Prescriptions</h5>
                <span class="badge bg-success">{{ count($activePrescriptions ?? []) ?? 2 }} Active</span>
            </div>
            <div class="card-body p-0">
                @if(isset($activePrescriptions) && count($activePrescriptions) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Medication</th>
                                    <th>Prescribed For</th>
                                    <th>Dosage</th>
                                    <th>Prescribing Dentist</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activePrescriptions as $prescription)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary-light text-primary rounded-circle me-2" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-capsule"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $prescription->medication_name ?? 'Amoxicillin' }}</p>
                                                    <p class="mb-0 small text-muted">{{ $prescription->medication_type ?? 'Antibiotic' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $prescription->prescribed_for ?? 'Post-extraction infection prevention' }}</td>
                                        <td>{{ $prescription->dosage ?? '500mg, 3 times daily' }}</td>
                                        <td>Dr. {{ $prescription->dentist->name ?? 'John Doe' }}</td>
                                        <td>{{ date('M d, Y', strtotime($prescription->start_date ?? now()->subDays(3))) }}</td>
                                        <td>{{ date('M d, Y', strtotime($prescription->end_date ?? now()->addDays(7))) }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#prescriptionDetailModal" data-prescription-id="{{ $prescription->id ?? 1 }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#refillRequestModal" data-prescription-id="{{ $prescription->id ?? 1 }}">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-check text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No active prescriptions</h5>
                        <p class="text-muted">You don't have any active prescriptions at this time</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Past Prescriptions -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Past Prescriptions</h5>
                <span class="badge bg-secondary">{{ count($pastPrescriptions ?? []) ?? 4 }} Past</span>
            </div>
            <div class="card-body p-0">
                @if(isset($pastPrescriptions) && count($pastPrescriptions) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Medication</th>
                                    <th>Prescribed For</th>
                                    <th>Prescribing Dentist</th>
                                    <th>Date Range</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pastPrescriptions ?? [] as $prescription)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light text-secondary rounded-circle me-2" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-capsule"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $prescription->medication_name ?? 'Ibuprofen' }}</p>
                                                    <p class="mb-0 small text-muted">{{ $prescription->medication_type ?? 'Anti-inflammatory' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $prescription->prescribed_for ?? 'Post-root canal pain management' }}</td>
                                        <td>Dr. {{ $prescription->dentist->name ?? 'Jane Smith' }}</td>
                                        <td>
                                            <small>
                                                {{ date('M d, Y', strtotime($prescription->start_date ?? now()->subMonths(rand(1, 6))->subDays(rand(1, 15)))) }} - 
                                                {{ date('M d, Y', strtotime($prescription->end_date ?? now()->subMonths(rand(1, 5))->subDays(rand(1, 15)))) }}
                                            </small>
                                        </td>
                                        <td><span class="badge bg-secondary">Completed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#prescriptionDetailModal" data-prescription-id="{{ $prescription->id ?? rand(100, 200) }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No past prescriptions</h5>
                        <p class="text-muted">Your prescription history will appear here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Medication Information Card -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Medication Information</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="medicationAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#antibiotic" aria-expanded="true" aria-controls="antibiotic">
                                Antibiotics
                            </button>
                        </h2>
                        <div id="antibiotic" class="accordion-collapse collapse show" data-bs-parent="#medicationAccordion">
                            <div class="accordion-body">
                                <p>Antibiotics are prescribed to prevent or treat bacterial infections before or after certain dental procedures. Common dental antibiotics include:</p>
                                <ul>
                                    <li><strong>Amoxicillin:</strong> Broad-spectrum antibiotic for infection treatment or prevention</li>
                                    <li><strong>Clindamycin:</strong> Alternative for patients allergic to penicillin</li>
                                    <li><strong>Metronidazole:</strong> Often used for specific anaerobic bacterial infections</li>
                                </ul>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Always complete the full course of antibiotics, even if symptoms improve before the medication is finished.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#painRelievers" aria-expanded="false" aria-controls="painRelievers">
                                Pain Relievers
                            </button>
                        </h2>
                        <div id="painRelievers" class="accordion-collapse collapse" data-bs-parent="#medicationAccordion">
                            <div class="accordion-body">
                                <p>Pain relievers are commonly prescribed for post-procedure discomfort or dental pain. Types include:</p>
                                <ul>
                                    <li><strong>Ibuprofen (Advil, Motrin):</strong> Non-steroidal anti-inflammatory drug (NSAID) that reduces pain and inflammation</li>
                                    <li><strong>Acetaminophen (Tylenol):</strong> Pain reliever without anti-inflammatory properties</li>
                                    <li><strong>Stronger prescription options:</strong> May contain opioids like hydrocodone for severe pain management</li>
                                </ul>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Take pain medications only as prescribed. Opioid medications should be used cautiously and only for a short period.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#anxietyMeds" aria-expanded="false" aria-controls="anxietyMeds">
                                Anxiety Medication
                            </button>
                        </h2>
                        <div id="anxietyMeds" class="accordion-collapse collapse" data-bs-parent="#medicationAccordion">
                            <div class="accordion-body">
                                <p>For patients with dental anxiety, dentists may prescribe anti-anxiety medications to help make appointments more comfortable:</p>
                                <ul>
                                    <li><strong>Diazepam (Valium):</strong> May be prescribed for use before a dental appointment</li>
                                    <li><strong>Triazolam (Halcion):</strong> Can be used for conscious sedation during dental procedures</li>
                                </ul>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i> These medications can cause drowsiness. Arrange for someone to drive you to and from your appointment if you take anti-anxiety medication.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Detail Modal -->
<div class="modal fade" id="prescriptionDetailModal" tabindex="-1" aria-labelledby="prescriptionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prescriptionDetailModalLabel">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="prescription-detail">
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <h4>Amoxicillin</h4>
                            <p class="text-muted mb-0">Antibiotic</p>
                        </div>
                        <span class="badge bg-success align-self-start">Active</span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5">
                            <p class="text-muted mb-0">Prescribed By:</p>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">Dr. John Doe</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5">
                            <p class="text-muted mb-0">Date Prescribed:</p>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">June 5, 2024</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5">
                            <p class="text-muted mb-0">Valid Until:</p>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">June 15, 2024</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5">
                            <p class="text-muted mb-0">Dosage:</p>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">500mg, 3 times daily for 10 days</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5">
                            <p class="text-muted mb-0">Related Procedure:</p>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">Tooth Extraction (Tooth #18)</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-5">
                            <p class="text-muted mb-0">Special Instructions:</p>
                        </div>
                        <div class="col-7">
                            <p class="mb-0">Take with food. Complete the entire prescription even if symptoms improve.</p>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="bi bi-info-circle" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Side Effects to Watch For</h6>
                                <p class="mb-0">Diarrhea, stomach upset, or allergic reactions (rash, itching, swelling). Contact your dentist if you experience severe side effects.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="downloadPrescriptionDetail">
                    <i class="bi bi-download me-1"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Refill Request Modal -->
<div class="modal fade" id="refillRequestModal" tabindex="-1" aria-labelledby="refillRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refillRequestModalLabel">Request Medication Refill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="refillRequestForm">
                    <div class="mb-3">
                        <label for="medicationName" class="form-label">Medication</label>
                        <input type="text" class="form-control" id="medicationName" value="Amoxicillin 500mg" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="prescribingDentist" class="form-label">Prescribing Dentist</label>
                        <input type="text" class="form-control" id="prescribingDentist" value="Dr. John Doe" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="originalPrescriptionDate" class="form-label">Original Prescription Date</label>
                        <input type="text" class="form-control" id="originalPrescriptionDate" value="June 5, 2024" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="refillReason" class="form-label">Reason for Refill Request</label>
                        <textarea class="form-control" id="refillReason" rows="3" placeholder="Please explain why you need a refill..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pharmacyPreference" class="form-label">Preferred Pharmacy</label>
                        <select class="form-select" id="pharmacyPreference" required>
                            <option value="">Select pharmacy...</option>
                            <option value="previous">Same as previous (City Pharmacy)</option>
                            <option value="new">Use different pharmacy</option>
                        </select>
                    </div>
                    <div class="mb-3" id="newPharmacyDetails" style="display: none;">
                        <label for="newPharmacyName" class="form-label">New Pharmacy Details</label>
                        <input type="text" class="form-control mb-2" id="newPharmacyName" placeholder="Pharmacy name">
                        <input type="text" class="form-control mb-2" id="newPharmacyAddress" placeholder="Address">
                        <input type="text" class="form-control" id="newPharmacyPhone" placeholder="Phone number">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="urgentRequest">
                        <label class="form-check-label" for="urgentRequest">This is an urgent request</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitRefillRequest">Submit Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        document.getElementById('searchPrescription').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('table tbody tr');
            
            tableRows.forEach(row => {
                const medicationName = row.querySelector('td:first-child').textContent.toLowerCase();
                const prescribedFor = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (medicationName.includes(searchValue) || prescribedFor.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Filter Status
        document.getElementById('filterStatus').addEventListener('change', function() {
            const filterValue = this.value;
            const tableRows = document.querySelectorAll('table tbody tr');
            
            if (filterValue === 'all') {
                tableRows.forEach(row => {
                    row.style.display = '';
                });
                return;
            }
            
            tableRows.forEach(row => {
                const status = row.querySelector('td:nth-child(5) .badge')?.textContent.toLowerCase();
                if (filterValue === 'active' && status === 'active') {
                    row.style.display = '';
                } else if (filterValue === 'expired' && status === 'completed') {
                    row.style.display = '';
                } else if (filterValue === 'refill' && row.querySelector('td:last-child button.btn-success')) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Download and Print functionality
        document.getElementById('downloadPrescriptions').addEventListener('click', function() {
            alert('Your prescription list is being downloaded as PDF.');
        });
        
        document.getElementById('printPrescriptions').addEventListener('click', function() {
            window.print();
        });
        
        document.getElementById('downloadPrescriptionDetail').addEventListener('click', function() {
            alert('Prescription details are being downloaded as PDF.');
        });
        
        // Show/hide pharmacy details based on selection
        document.getElementById('pharmacyPreference').addEventListener('change', function() {
            const newPharmacyDetails = document.getElementById('newPharmacyDetails');
            if (this.value === 'new') {
                newPharmacyDetails.style.display = 'block';
            } else {
                newPharmacyDetails.style.display = 'none';
            }
        });
        
        // Handle refill request submission
        document.getElementById('submitRefillRequest').addEventListener('click', function() {
            alert('Your refill request has been submitted. The dental office will contact you soon.');
            const modal = bootstrap.Modal.getInstance(document.getElementById('refillRequestModal'));
            modal.hide();
        });
        
        // Prescription detail modal dynamic content (would be populated from backend data)
        const prescriptionDetailModal = document.getElementById('prescriptionDetailModal');
        prescriptionDetailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const prescriptionId = button.getAttribute('data-prescription-id');
            
            // In a real app, you would fetch the prescription details using the ID
            // For demo purposes, we're not changing the content based on ID
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Print styles */
    @media print {
        .sidebar, .header, .card-header, .btn, #searchPrescription, 
        #filterStatus, .page-title, .modal, .accordion-button::after {
            display: none !important;
        }
        
        .content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
            margin-bottom: 20px !important;
        }
        
        .accordion-button {
            pointer-events: none;
        }
        
        .accordion-collapse {
            display: block !important;
        }
        
        .accordion-body {
            padding-bottom: 20px !important;
        }
    }
    
    .bg-primary-light {
        background-color: #e3f2fd;
    }
</style>
@endpush