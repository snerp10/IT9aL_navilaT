@extends('layouts.patient')

@section('title', 'Treatment History')

@section('content')
<div class="row fade-in">
    <!-- Info Card -->
    <div class="col-12 mb-4">
        <div class="card bg-light">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-primary text-white me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">Treatment History</h4>
                        <p class="mb-0">Complete record of your dental procedures and treatments. Your dental health journey at a glance.</p>
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
                            <input type="text" class="form-control" placeholder="Search treatments..." id="searchTreatment">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <select class="form-select" id="filterTreatmentType">
                            <option value="all">All Treatment Types</option>
                            <option value="preventive">Preventive</option>
                            <option value="restorative">Restorative</option>
                            <option value="cosmetic">Cosmetic</option>
                            <option value="surgical">Surgical</option>
                            <option value="orthodontic">Orthodontic</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex justify-content-md-end mt-3 mt-md-0">
                        <button class="btn btn-outline-primary me-2" id="downloadHistory">
                            <i class="bi bi-download me-1"></i> Export History
                        </button>
                        <button class="btn btn-outline-secondary" id="printHistory">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Treatment Summary Card -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Treatment Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-4 mb-md-0 text-center">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-3 bg-primary-light text-primary" style="width: 60px; height: 60px;">
                                    <i class="bi bi-calendar2-check" style="font-size: 1.5rem;"></i>
                                </div>
                                <h3>{{ $totalProcedures ?? 12 }}</h3>
                                <p class="text-muted mb-0">Total Procedures</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4 mb-md-0 text-center">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-3 bg-success-light text-success" style="width: 60px; height: 60px;">
                                    <i class="bi bi-shield-check" style="font-size: 1.5rem;"></i>
                                </div>
                                <h3>{{ $preventiveCare ?? 5 }}</h3>
                                <p class="text-muted mb-0">Preventive Care</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4 mb-md-0 text-center">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-3 bg-info-light text-info" style="width: 60px; height: 60px;">
                                    <i class="bi bi-tools" style="font-size: 1.5rem;"></i>
                                </div>
                                <h3>{{ $restorativeCare ?? 6 }}</h3>
                                <p class="text-muted mb-0">Restorative Work</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="avatar mx-auto mb-3 bg-warning-light text-warning" style="width: 60px; height: 60px;">
                                    <i class="bi bi-calendar-plus" style="font-size: 1.5rem;"></i>
                                </div>
                                <h3>{{ date('M Y', strtotime($nextCheckup ?? now()->addMonths(3))) }}</h3>
                                <p class="text-muted mb-0">Next Checkup</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Treatment Timeline -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Treatment Timeline</h5>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary active" data-bs-view="timeline">Timeline</button>
                    <button type="button" class="btn btn-outline-primary" data-bs-view="table">Table</button>
                </div>
            </div>
            <div class="card-body">
                <div id="timelineView">
                    <div class="timeline-container">
                        <!-- 2024 -->
                        <div class="timeline-year">
                            <div class="timeline-year-label">2024</div>
                            <div class="timeline-events">
                                <div class="timeline-event">
                                    <div class="timeline-date">MAY 15</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-primary me-2">Preventive</span>
                                                    <h6 class="mb-0">Regular Checkup and Cleaning</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. John Doe</p>
                                                <p class="mb-0">Routine dental examination and professional cleaning. No significant issues found.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="1">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="timeline-event">
                                    <div class="timeline-date">JAN 30</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-info me-2">Restorative</span>
                                                    <h6 class="mb-0">Tooth Filling - Tooth #16</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. Jane Smith</p>
                                                <p class="mb-0">Composite filling to repair a cavity on the upper right molar.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="2">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2023 -->
                        <div class="timeline-year">
                            <div class="timeline-year-label">2023</div>
                            <div class="timeline-events">
                                <div class="timeline-event">
                                    <div class="timeline-date">NOV 18</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-primary me-2">Preventive</span>
                                                    <h6 class="mb-0">Regular Checkup and Cleaning</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. John Doe</p>
                                                <p class="mb-0">Routine dental examination and professional cleaning. Small cavity detected on tooth #16.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="3">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="timeline-event">
                                    <div class="timeline-date">AUG 05</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-warning me-2">Surgical</span>
                                                    <h6 class="mb-0">Wisdom Tooth Extraction - Tooth #38</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. Michael Brown</p>
                                                <p class="mb-0">Extraction of impacted lower left wisdom tooth due to recurrent infections.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="4">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="timeline-event">
                                    <div class="timeline-date">MAY 20</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-primary me-2">Preventive</span>
                                                    <h6 class="mb-0">Regular Checkup and Cleaning</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. John Doe</p>
                                                <p class="mb-0">Routine dental examination and professional cleaning. X-rays taken. Lower left wisdom tooth showing signs of impaction.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="5">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2022 -->
                        <div class="timeline-year">
                            <div class="timeline-year-label">2022</div>
                            <div class="timeline-events">
                                <div class="timeline-event">
                                    <div class="timeline-date">NOV 10</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-primary me-2">Preventive</span>
                                                    <h6 class="mb-0">Regular Checkup and Cleaning</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. John Doe</p>
                                                <p class="mb-0">Routine dental examination and professional cleaning. No significant issues found.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="6">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="timeline-event">
                                    <div class="timeline-date">MAY 25</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-info me-2">Restorative</span>
                                                    <h6 class="mb-0">Root Canal Treatment - Tooth #36</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. Sarah Wilson</p>
                                                <p class="mb-0">Root canal therapy on lower left first molar due to deep decay reaching the pulp.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="7">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="timeline-event">
                                    <div class="timeline-date">APR 12</div>
                                    <div class="timeline-content">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-primary me-2">Emergency</span>
                                                    <h6 class="mb-0">Emergency Visit - Tooth Pain</h6>
                                                </div>
                                                <p class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i> Dr. John Doe</p>
                                                <p class="mb-0">Severe tooth pain in lower left first molar. X-rays revealed deep decay. Pain management provided and root canal recommended.</p>
                                                <div class="d-flex mt-3">
                                                    <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="8">
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Load More Button -->
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" id="loadMoreHistory">
                                Load More History
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="tableView" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Treatment Type</th>
                                    <th>Procedure</th>
                                    <th>Dentist</th>
                                    <th>Tooth/Area</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- 2024 Treatments -->
                                <tr>
                                    <td>May 15, 2024</td>
                                    <td><span class="badge bg-primary">Preventive</span></td>
                                    <td>Regular Checkup and Cleaning</td>
                                    <td>Dr. John Doe</td>
                                    <td>Full Mouth</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="1">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jan 30, 2024</td>
                                    <td><span class="badge bg-info">Restorative</span></td>
                                    <td>Tooth Filling</td>
                                    <td>Dr. Jane Smith</td>
                                    <td>Tooth #16</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="2">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- 2023 Treatments -->
                                <tr>
                                    <td>Nov 18, 2023</td>
                                    <td><span class="badge bg-primary">Preventive</span></td>
                                    <td>Regular Checkup and Cleaning</td>
                                    <td>Dr. John Doe</td>
                                    <td>Full Mouth</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="3">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Aug 05, 2023</td>
                                    <td><span class="badge bg-warning">Surgical</span></td>
                                    <td>Wisdom Tooth Extraction</td>
                                    <td>Dr. Michael Brown</td>
                                    <td>Tooth #38</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="4">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>May 20, 2023</td>
                                    <td><span class="badge bg-primary">Preventive</span></td>
                                    <td>Regular Checkup and Cleaning</td>
                                    <td>Dr. John Doe</td>
                                    <td>Full Mouth</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="5">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- 2022 Treatments -->
                                <tr>
                                    <td>Nov 10, 2022</td>
                                    <td><span class="badge bg-primary">Preventive</span></td>
                                    <td>Regular Checkup and Cleaning</td>
                                    <td>Dr. John Doe</td>
                                    <td>Full Mouth</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="6">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>May 25, 2022</td>
                                    <td><span class="badge bg-info">Restorative</span></td>
                                    <td>Root Canal Treatment</td>
                                    <td>Dr. Sarah Wilson</td>
                                    <td>Tooth #36</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="7">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Apr 12, 2022</td>
                                    <td><span class="badge bg-primary">Emergency</span></td>
                                    <td>Emergency Visit - Tooth Pain</td>
                                    <td>Dr. John Doe</td>
                                    <td>Tooth #36</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#treatmentDetailModal" data-treatment-id="8">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="text-muted">Showing 1-8 of 12 records</span>
                        </div>
                        <nav aria-label="Treatment history pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overall Dental Health Card -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Dental Health Progress</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h6 class="mb-3">Oral Health Score</h6>
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                        </div>
                        <p class="text-muted small">Your current oral health score based on recent examinations</p>
                        
                        <div class="mt-4">
                            <h6 class="mb-3">Areas of Improvement:</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <div class="avatar bg-warning-light text-warning" style="width: 36px; height: 36px;">
                                        <i class="bi bi-brush"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Brushing Technique</h6>
                                    <p class="text-muted mb-0 small">Focus on proper technique for upper right molars</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="avatar bg-info-light text-info" style="width: 36px; height: 36px;">
                                        <i class="bi bi-cup-straw"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Sugary Beverage Consumption</h6>
                                    <p class="text-muted mb-0 small">Consider reducing sugary drinks to prevent future cavities</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="mb-3">Treatment Breakdown</h6>
                        <div class="mb-3">
                            <canvas id="treatmentTypeChart"></canvas>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-4">
                                <div class="avatar mx-auto mb-2 bg-primary-light text-primary" style="width: 36px; height: 36px;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <p class="small mb-0">Preventive</p>
                                <h6>42%</h6>
                            </div>
                            <div class="col-4">
                                <div class="avatar mx-auto mb-2 bg-info-light text-info" style="width: 36px; height: 36px;">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <p class="small mb-0">Restorative</p>
                                <h6>33%</h6>
                            </div>
                            <div class="col-4">
                                <div class="avatar mx-auto mb-2 bg-warning-light text-warning" style="width: 36px; height: 36px;">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <p class="small mb-0">Other</p>
                                <h6>25%</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Treatment Detail Modal -->
<div class="modal fade" id="treatmentDetailModal" tabindex="-1" aria-labelledby="treatmentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="treatmentDetailModalLabel">Treatment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Treatment info - would be dynamically populated in real app -->
                <div class="treatment-detail">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 id="modalTreatmentTitle">Root Canal Treatment - Tooth #36</h4>
                            <p class="text-muted mb-0" id="modalTreatmentDate">May 25, 2022</p>
                        </div>
                        <span class="badge bg-info" id="modalTreatmentType">Restorative</span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Treatment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <p class="text-muted mb-0">Dentist:</p>
                                        </div>
                                        <div class="col-7">
                                            <p class="mb-0" id="modalDentist">Dr. Sarah Wilson</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <p class="text-muted mb-0">Tooth/Area:</p>
                                        </div>
                                        <div class="col-7">
                                            <p class="mb-0" id="modalToothArea">Lower Left First Molar (Tooth #36)</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <p class="text-muted mb-0">Anesthesia:</p>
                                        </div>
                                        <div class="col-7">
                                            <p class="mb-0" id="modalAnesthesia">Local (Lidocaine 2%)</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">
                                            <p class="text-muted mb-0">Duration:</p>
                                        </div>
                                        <div class="col-7">
                                            <p class="mb-0" id="modalDuration">90 minutes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Clinical Notes</h6>
                                </div>
                                <div class="card-body">
                                    <p id="modalClinicalNotes">Root canal therapy performed on tooth #36 due to irreversible pulpitis. Canals were cleaned, shaped, and obturated with gutta-percha. Post-operative instructions provided to patient. Patient tolerated procedure well.</p>
                                    
                                    <div class="alert alert-info mt-3 mb-0">
                                        <div class="d-flex">
                                            <div>
                                                <i class="bi bi-info-circle me-2"></i>
                                            </div>
                                            <div>
                                                <h6 class="alert-heading mb-1">Follow-up Required</h6>
                                                <p class="mb-0 small">Crown placement recommended within 1 month to protect the treated tooth.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Materials Used</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="modalMaterials">
                                        <li class="list-group-item px-0 py-2">Gutta-percha points</li>
                                        <li class="list-group-item px-0 py-2">Root canal sealer</li>
                                        <li class="list-group-item px-0 py-2">Temporary filling material</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Post-Treatment Care</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="modalPostCare">
                                        <li class="list-group-item px-0 py-2">Avoid chewing on the treated side for 24 hours</li>
                                        <li class="list-group-item px-0 py-2">Take prescribed medications as directed</li>
                                        <li class="list-group-item px-0 py-2">Return for permanent restoration within 1 month</li>
                                        <li class="list-group-item px-0 py-2">Contact office if pain persists or worsens</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="downloadTreatmentDetail">
                    <i class="bi bi-download me-1"></i> Download Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart
        const ctx = document.getElementById('treatmentTypeChart').getContext('2d');
        const treatmentChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Preventive', 'Restorative', 'Other'],
                datasets: [{
                    data: [42, 33, 25],
                    backgroundColor: [
                        '#e3f2fd',
                        '#e0f7fa',
                        '#fff3e0'
                    ],
                    borderColor: [
                        '#1976d2',
                        '#0097a7',
                        '#ffb300'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
        
        // Search functionality
        document.getElementById('searchTreatment').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            
            // Search in timeline view
            const timelineEvents = document.querySelectorAll('.timeline-event');
            timelineEvents.forEach(event => {
                const title = event.querySelector('h6').textContent.toLowerCase();
                const dentist = event.querySelector('.text-muted').textContent.toLowerCase();
                const desc = event.querySelector('p:last-of-type').textContent.toLowerCase();
                
                if (title.includes(searchValue) || dentist.includes(searchValue) || desc.includes(searchValue)) {
                    event.style.display = '';
                    // Make sure parent year is visible
                    event.closest('.timeline-year').style.display = '';
                } else {
                    event.style.display = 'none';
                }
            });
            
            // Check if all events in a year are hidden, hide the year
            const timelineYears = document.querySelectorAll('.timeline-year');
            timelineYears.forEach(year => {
                const visibleEvents = year.querySelectorAll('.timeline-event[style="display: none;"]');
                if (visibleEvents.length === year.querySelectorAll('.timeline-event').length) {
                    year.style.display = 'none';
                } else {
                    year.style.display = '';
                }
            });
            
            // Search in table view
            const tableRows = document.querySelectorAll('#tableView tbody tr');
            tableRows.forEach(row => {
                const procedure = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const dentist = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const tooth = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                
                if (procedure.includes(searchValue) || dentist.includes(searchValue) || tooth.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Filter functionality
        document.getElementById('filterTreatmentType').addEventListener('change', function() {
            const filterValue = this.value.toLowerCase();
            
            if (filterValue === 'all') {
                document.querySelectorAll('.timeline-event').forEach(event => {
                    event.style.display = '';
                });
                document.querySelectorAll('.timeline-year').forEach(year => {
                    year.style.display = '';
                });
                document.querySelectorAll('#tableView tbody tr').forEach(row => {
                    row.style.display = '';
                });
                return;
            }
            
            // Filter timeline view
            const timelineEvents = document.querySelectorAll('.timeline-event');
            timelineEvents.forEach(event => {
                const badge = event.querySelector('.badge').textContent.toLowerCase();
                
                if (badge === filterValue) {
                    event.style.display = '';
                    // Make sure parent year is visible
                    event.closest('.timeline-year').style.display = '';
                } else {
                    event.style.display = 'none';
                }
            });
            
            // Check if all events in a year are hidden, hide the year
            const timelineYears = document.querySelectorAll('.timeline-year');
            timelineYears.forEach(year => {
                const allEvents = year.querySelectorAll('.timeline-event');
                const hiddenEvents = year.querySelectorAll('.timeline-event[style="display: none;"]');
                
                if (hiddenEvents.length === allEvents.length) {
                    year.style.display = 'none';
                }
            });
            
            // Filter table view
            const tableRows = document.querySelectorAll('#tableView tbody tr');
            tableRows.forEach(row => {
                const badge = row.querySelector('td:nth-child(2) .badge').textContent.toLowerCase();
                
                if (badge === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Toggle between timeline and table view
        document.querySelectorAll('[data-bs-view]').forEach(button => {
            button.addEventListener('click', function() {
                const viewType = this.getAttribute('data-bs-view');
                
                // Update active button
                document.querySelectorAll('[data-bs-view]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Show/hide appropriate view
                if (viewType === 'timeline') {
                    document.getElementById('timelineView').style.display = 'block';
                    document.getElementById('tableView').style.display = 'none';
                } else {
                    document.getElementById('timelineView').style.display = 'none';
                    document.getElementById('tableView').style.display = 'block';
                }
            });
        });
        
        // Print and download functionality
        document.getElementById('printHistory').addEventListener('click', function() {
            window.print();
        });
        
        document.getElementById('downloadHistory').addEventListener('click', function() {
            alert('Your treatment history is being exported as PDF.');
        });
        
        document.getElementById('downloadTreatmentDetail').addEventListener('click', function() {
            alert('Treatment details are being downloaded as PDF.');
        });
        
        // Load more history
        document.getElementById('loadMoreHistory').addEventListener('click', function() {
            alert('Loading more treatment history...');
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            
            // In a real app, this would load more history from the server
            setTimeout(() => {
                this.innerHTML = 'No More Records';
            }, 2000);
        });
        
        // Treatment detail modal dynamic content (would be populated from backend data)
        const treatmentDetailModal = document.getElementById('treatmentDetailModal');
        treatmentDetailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const treatmentId = button.getAttribute('data-treatment-id');
            
            // In a real app, you would fetch the treatment details using the ID
            // For demo purposes, we'll just show different content based on the ID
            const treatments = {
                "1": {
                    title: "Regular Checkup and Cleaning",
                    date: "May 15, 2024",
                    type: "Preventive",
                    dentist: "Dr. John Doe",
                    toothArea: "Full Mouth",
                    anesthesia: "None",
                    duration: "45 minutes",
                    notes: "Routine dental examination and professional cleaning. All teeth in good condition. Slight plaque buildup observed on upper right molars.",
                    materials: ["Polishing paste", "Fluoride treatment", "Dental floss"],
                    postCare: [
                        "Continue regular brushing twice daily",
                        "Floss daily",
                        "Schedule next checkup in 6 months"
                    ],
                    followUp: false
                },
                "7": {
                    title: "Root Canal Treatment - Tooth #36",
                    date: "May 25, 2022",
                    type: "Restorative",
                    dentist: "Dr. Sarah Wilson",
                    toothArea: "Lower Left First Molar (Tooth #36)",
                    anesthesia: "Local (Lidocaine 2%)",
                    duration: "90 minutes",
                    notes: "Root canal therapy performed on tooth #36 due to irreversible pulpitis. Canals were cleaned, shaped, and obturated with gutta-percha. Post-operative instructions provided to patient. Patient tolerated procedure well.",
                    materials: ["Gutta-percha points", "Root canal sealer", "Temporary filling material"],
                    postCare: [
                        "Avoid chewing on the treated side for 24 hours",
                        "Take prescribed medications as directed",
                        "Return for permanent restoration within 1 month",
                        "Contact office if pain persists or worsens"
                    ],
                    followUp: true
                }
            };
            
            // Use treatment ID 7 as default if not found
            const treatment = treatments[treatmentId] || treatments["7"];
            
            // Update modal content
            document.getElementById('modalTreatmentTitle').textContent = treatment.title;
            document.getElementById('modalTreatmentDate').textContent = treatment.date;
            document.getElementById('modalTreatmentType').textContent = treatment.type;
            document.getElementById('modalTreatmentType').className = `badge ${treatment.type === 'Preventive' ? 'bg-primary' : 'bg-info'}`;
            document.getElementById('modalDentist').textContent = treatment.dentist;
            document.getElementById('modalToothArea').textContent = treatment.toothArea;
            document.getElementById('modalAnesthesia').textContent = treatment.anesthesia;
            document.getElementById('modalDuration').textContent = treatment.duration;
            document.getElementById('modalClinicalNotes').textContent = treatment.notes;
            
            // Update materials list
            const materialsList = document.getElementById('modalMaterials');
            materialsList.innerHTML = '';
            treatment.materials.forEach(material => {
                const li = document.createElement('li');
                li.className = 'list-group-item px-0 py-2';
                li.textContent = material;
                materialsList.appendChild(li);
            });
            
            // Update post-care list
            const postCareList = document.getElementById('modalPostCare');
            postCareList.innerHTML = '';
            treatment.postCare.forEach(care => {
                const li = document.createElement('li');
                li.className = 'list-group-item px-0 py-2';
                li.textContent = care;
                postCareList.appendChild(li);
            });
            
            // Show/hide follow-up alert
            const followUpAlert = document.querySelector('.alert-info');
            followUpAlert.style.display = treatment.followUp ? 'block' : 'none';
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Timeline styling */
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-year {
        margin-bottom: 30px;
    }
    
    .timeline-year-label {
        background-color: var(--primary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .timeline-events {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-events:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: var(--gray-300);
    }
    
    .timeline-event {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-event:last-child {
        margin-bottom: 0;
    }
    
    .timeline-date {
        position: absolute;
        left: -30px;
        top: 0;
        width: 60px;
        text-align: right;
        padding-right: 15px;
        color: var(--gray-600);
        font-weight: 500;
        font-size: 0.875rem;
    }
    
    .timeline-date:before {
        content: '';
        position: absolute;
        right: -11px;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: var(--primary-color);
        border: 2px solid white;
        z-index: 1;
    }
    
    .timeline-content {
        padding-left: 30px;
    }
    
    /* Background colors for cards */
    .bg-primary-light {
        background-color: #e3f2fd;
    }
    
    .bg-success-light {
        background-color: #e8f5e9;
    }
    
    .bg-info-light {
        background-color: #e0f7fa;
    }
    
    .bg-warning-light {
        background-color: #fff3e0;
    }
    
    /* Print styles */
    @media print {
        .sidebar, .header, .card-header button, .btn, #searchTreatment, 
        #filterTreatmentType, .page-title, .modal, .btn-group,
        #loadMoreHistory {
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
        
        #tableView {
            display: block !important;
        }
        
        #timelineView {
            display: none !important;
        }
    }
</style>
@endpush