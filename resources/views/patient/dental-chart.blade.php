@extends('layouts.patient')

@section('title', 'Dental Chart')

@section('content')
<div class="row fade-in">
    <!-- Info Card -->
    <div class="col-12 mb-4">
        <div class="card bg-light">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-primary text-white me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-grid-3x3"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">Dental Chart</h4>
                        <p class="mb-0">Visual representation of your dental health status. Click on any tooth to see details.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Legend -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Legend</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="tooth healthy me-2" style="width: 30px; height: 30px;"></div>
                        <span>Healthy</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="tooth treated me-2" style="width: 30px; height: 30px;"></div>
                        <span>Treated</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="tooth needs-attention me-2" style="width: 30px; height: 30px;"></div>
                        <span>Needs Attention</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="tooth problem me-2" style="width: 30px; height: 30px;"></div>
                        <span>Problem</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="tooth missing me-2" style="width: 30px; height: 30px; background-color: #f5f5f5; border: 1px dashed #adb5bd;"></div>
                        <span>Missing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dental Chart Section -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Teeth Map</h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary" id="printDentalChart">
                        <i class="bi bi-printer me-1"></i> Print Chart
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Adult Teeth Chart -->
                <div class="dental-chart-container">
                    <div class="text-center mb-4">
                        <div class="btn-group">
                            <input type="radio" class="btn-check" name="chart-type" id="adult-chart" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="adult-chart">Adult</label>
                            
                            <input type="radio" class="btn-check" name="chart-type" id="child-chart" autocomplete="off">
                            <label class="btn btn-outline-primary" for="child-chart">Child</label>
                        </div>
                    </div>
                    
                    <div id="adult-tooth-chart">
                        <!-- Upper Jaw -->
                        <div class="text-center mb-2"><small class="text-muted">Upper Right</small></div>
                        <div class="tooth-chart upper-jaw mb-4">
                            <!-- Upper Right Quadrant (1) -->
                            <div class="tooth healthy" data-tooth-num="18" data-toggle="tooltip" title="Wisdom Tooth - Upper Right (18)">18</div>
                            <div class="tooth healthy" data-tooth-num="17" data-toggle="tooltip" title="Molar - Upper Right (17)">17</div>
                            <div class="tooth treated" data-tooth-num="16" data-toggle="tooltip" title="Molar - Upper Right (16)">16</div>
                            <div class="tooth healthy" data-tooth-num="15" data-toggle="tooltip" title="Premolar - Upper Right (15)">15</div>
                            <div class="tooth needs-attention" data-tooth-num="14" data-toggle="tooltip" title="Premolar - Upper Right (14)">14</div>
                            <div class="tooth healthy" data-tooth-num="13" data-toggle="tooltip" title="Canine - Upper Right (13)">13</div>
                            <div class="tooth healthy" data-tooth-num="12" data-toggle="tooltip" title="Incisor - Upper Right (12)">12</div>
                            <div class="tooth healthy" data-tooth-num="11" data-toggle="tooltip" title="Incisor - Upper Right (11)">11</div>
                            
                            <!-- Upper Left Quadrant (2) -->
                            <div class="tooth healthy" data-tooth-num="21" data-toggle="tooltip" title="Incisor - Upper Left (21)">21</div>
                            <div class="tooth healthy" data-tooth-num="22" data-toggle="tooltip" title="Incisor - Upper Left (22)">22</div>
                            <div class="tooth healthy" data-tooth-num="23" data-toggle="tooltip" title="Canine - Upper Left (23)">23</div>
                            <div class="tooth healthy" data-tooth-num="24" data-toggle="tooltip" title="Premolar - Upper Left (24)">24</div>
                            <div class="tooth healthy" data-tooth-num="25" data-toggle="tooltip" title="Premolar - Upper Left (25)">25</div>
                            <div class="tooth problem" data-tooth-num="26" data-toggle="tooltip" title="Molar - Upper Left (26)">26</div>
                            <div class="tooth healthy" data-tooth-num="27" data-toggle="tooltip" title="Molar - Upper Left (27)">27</div>
                            <div class="tooth missing" data-tooth-num="28" data-toggle="tooltip" title="Wisdom Tooth - Upper Left (28)">28</div>
                        </div>
                        <div class="text-center mb-2"><small class="text-muted">Upper Left</small></div>
                        
                        <div class="jaw-separator my-4 text-center">
                            <span class="text-muted">- - - - - - - - - - - - - - - - -</span>
                        </div>
                        
                        <div class="text-center mb-2"><small class="text-muted">Lower Right</small></div>
                        <!-- Lower Jaw -->
                        <div class="tooth-chart lower-jaw">
                            <!-- Lower Right Quadrant (4) -->
                            <div class="tooth healthy" data-tooth-num="48" data-toggle="tooltip" title="Wisdom Tooth - Lower Right (48)">48</div>
                            <div class="tooth healthy" data-tooth-num="47" data-toggle="tooltip" title="Molar - Lower Right (47)">47</div>
                            <div class="tooth healthy" data-tooth-num="46" data-toggle="tooltip" title="Molar - Lower Right (46)">46</div>
                            <div class="tooth healthy" data-tooth-num="45" data-toggle="tooltip" title="Premolar - Lower Right (45)">45</div>
                            <div class="tooth healthy" data-tooth-num="44" data-toggle="tooltip" title="Premolar - Lower Right (44)">44</div>
                            <div class="tooth treated" data-tooth-num="43" data-toggle="tooltip" title="Canine - Lower Right (43)">43</div>
                            <div class="tooth healthy" data-tooth-num="42" data-toggle="tooltip" title="Incisor - Lower Right (42)">42</div>
                            <div class="tooth healthy" data-tooth-num="41" data-toggle="tooltip" title="Incisor - Lower Right (41)">41</div>
                            
                            <!-- Lower Left Quadrant (3) -->
                            <div class="tooth healthy" data-tooth-num="31" data-toggle="tooltip" title="Incisor - Lower Left (31)">31</div>
                            <div class="tooth healthy" data-tooth-num="32" data-toggle="tooltip" title="Incisor - Lower Left (32)">32</div>
                            <div class="tooth healthy" data-tooth-num="33" data-toggle="tooltip" title="Canine - Lower Left (33)">33</div>
                            <div class="tooth healthy" data-tooth-num="34" data-toggle="tooltip" title="Premolar - Lower Left (34)">34</div>
                            <div class="tooth healthy" data-tooth-num="35" data-toggle="tooltip" title="Premolar - Lower Left (35)">35</div>
                            <div class="tooth treated" data-tooth-num="36" data-toggle="tooltip" title="Molar - Lower Left (36)">36</div>
                            <div class="tooth healthy" data-tooth-num="37" data-toggle="tooltip" title="Molar - Lower Left (37)">37</div>
                            <div class="tooth problem" data-tooth-num="38" data-toggle="tooltip" title="Wisdom Tooth - Lower Left (38)">38</div>
                        </div>
                        <div class="text-center mt-2"><small class="text-muted">Lower Left</small></div>
                    </div>
                    
                    <div id="child-tooth-chart" style="display: none;">
                        <!-- Upper Jaw (Child) -->
                        <div class="text-center mb-2"><small class="text-muted">Upper Right</small></div>
                        <div class="tooth-chart upper-jaw mb-4">
                            <!-- Upper Right Quadrant (5) -->
                            <div class="tooth healthy" data-tooth-num="55" data-toggle="tooltip" title="Molar - Upper Right (55)">55</div>
                            <div class="tooth healthy" data-tooth-num="54" data-toggle="tooltip" title="Molar - Upper Right (54)">54</div>
                            <div class="tooth healthy" data-tooth-num="53" data-toggle="tooltip" title="Canine - Upper Right (53)">53</div>
                            <div class="tooth healthy" data-tooth-num="52" data-toggle="tooltip" title="Incisor - Upper Right (52)">52</div>
                            <div class="tooth healthy" data-tooth-num="51" data-toggle="tooltip" title="Incisor - Upper Right (51)">51</div>
                            
                            <!-- Upper Left Quadrant (6) -->
                            <div class="tooth healthy" data-tooth-num="61" data-toggle="tooltip" title="Incisor - Upper Left (61)">61</div>
                            <div class="tooth healthy" data-tooth-num="62" data-toggle="tooltip" title="Incisor - Upper Left (62)">62</div>
                            <div class="tooth treated" data-tooth-num="63" data-toggle="tooltip" title="Canine - Upper Left (63)">63</div>
                            <div class="tooth healthy" data-tooth-num="64" data-toggle="tooltip" title="Molar - Upper Left (64)">64</div>
                            <div class="tooth needs-attention" data-tooth-num="65" data-toggle="tooltip" title="Molar - Upper Left (65)">65</div>
                        </div>
                        <div class="text-center mb-2"><small class="text-muted">Upper Left</small></div>
                        
                        <div class="jaw-separator my-4 text-center">
                            <span class="text-muted">- - - - - - - - - - -</span>
                        </div>
                        
                        <div class="text-center mb-2"><small class="text-muted">Lower Right</small></div>
                        <!-- Lower Jaw (Child) -->
                        <div class="tooth-chart lower-jaw">
                            <!-- Lower Right Quadrant (8) -->
                            <div class="tooth healthy" data-tooth-num="85" data-toggle="tooltip" title="Molar - Lower Right (85)">85</div>
                            <div class="tooth healthy" data-tooth-num="84" data-toggle="tooltip" title="Molar - Lower Right (84)">84</div>
                            <div class="tooth healthy" data-tooth-num="83" data-toggle="tooltip" title="Canine - Lower Right (83)">83</div>
                            <div class="tooth healthy" data-tooth-num="82" data-toggle="tooltip" title="Incisor - Lower Right (82)">82</div>
                            <div class="tooth healthy" data-tooth-num="81" data-toggle="tooltip" title="Incisor - Lower Right (81)">81</div>
                            
                            <!-- Lower Left Quadrant (7) -->
                            <div class="tooth healthy" data-tooth-num="71" data-toggle="tooltip" title="Incisor - Lower Left (71)">71</div>
                            <div class="tooth healthy" data-tooth-num="72" data-toggle="tooltip" title="Incisor - Lower Left (72)">72</div>
                            <div class="tooth healthy" data-tooth-num="73" data-toggle="tooltip" title="Canine - Lower Left (73)">73</div>
                            <div class="tooth problem" data-tooth-num="74" data-toggle="tooltip" title="Molar - Lower Left (74)">74</div>
                            <div class="tooth healthy" data-tooth-num="75" data-toggle="tooltip" title="Molar - Lower Left (75)">75</div>
                        </div>
                        <div class="text-center mt-2"><small class="text-muted">Lower Left</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Selected Tooth Details -->
    <div class="col-12 mb-4">
        <div class="card" id="selectedToothCard">
            <div class="card-header">
                <h5 class="mb-0">Tooth Details</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5" id="noToothSelected">
                    <i class="bi bi-hand-index-thumb text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No tooth selected</h5>
                    <p class="text-muted">Click on any tooth in the chart above to view its details</p>
                </div>
                
                <div id="toothDetails" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="tooth-image-container text-center">
                                <div class="position-relative d-inline-block">
                                    <div id="selectedToothVisual" class="tooth healthy mx-auto" style="width: 100px; height: 100px; font-size: 2rem;"></div>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill tooth-quadrant-badge">
                                        <span id="toothQuadrant"></span>
                                    </span>
                                </div>
                                <h3 class="mt-3 mb-0" id="toothNumber"></h3>
                                <p class="text-muted" id="toothName"></p>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Current Status</h6>
                                <div class="d-flex align-items-center">
                                    <span class="tooth-status-indicator" id="toothStatusIndicator"></span>
                                    <span class="ms-2" id="toothStatus">Healthy</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Last Treatment</h6>
                                <div id="lastTreatmentInfo">
                                    <p class="mb-1" id="lastTreatmentDate"></p>
                                    <p class="mb-1" id="lastTreatmentType"></p>
                                    <p class="mb-0" id="lastTreatmentDentist"></p>
                                </div>
                                <div id="noTreatmentInfo" style="display: none;">
                                    <p class="text-muted">No treatments recorded for this tooth</p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Recommendations</h6>
                                <p class="mb-0" id="toothRecommendations"></p>
                            </div>
                            
                            <div class="mt-4">
                                <button class="btn btn-outline-primary" id="viewTreatmentHistory">
                                    <i class="bi bi-clock-history me-1"></i> View Complete History
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Health Tips -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Dental Health Tips</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="text-center">
                            <i class="bi bi-brush text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Proper Brushing</h6>
                            <p class="small text-muted">Brush twice a day for at least 2 minutes using fluoride toothpaste</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="text-center">
                            <i class="bi bi-droplet text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Daily Flossing</h6>
                            <p class="small text-muted">Clean between teeth using floss or interdental brushes daily</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="text-center">
                            <i class="bi bi-cup-straw text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Limit Sugary Drinks</h6>
                            <p class="small text-muted">Reduce consumption of sugary and acidic drinks to protect enamel</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Regular Check-ups</h6>
                            <p class="small text-muted">Visit your dentist every 6 months for professional cleaning and examination</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tooth Detail Modal -->
<div class="modal fade" id="toothDetailModal" tabindex="-1" aria-labelledby="toothDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toothDetailModalLabel">Tooth Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="tooth modal-tooth mx-auto mb-2" id="modalToothDisplay"></div>
                        <h3 id="modalToothNumber"></h3>
                        <p class="text-muted" id="modalToothName"></p>
                    </div>
                    <div class="col-md-8">
                        <nav>
                            <div class="nav nav-tabs" id="tooth-tab" role="tablist">
                                <button class="nav-link active" id="treatment-tab" data-bs-toggle="tab" data-bs-target="#treatment" type="button" role="tab" aria-controls="treatment" aria-selected="true">Treatment History</button>
                                <button class="nav-link" id="diagnosis-tab" data-bs-toggle="tab" data-bs-target="#diagnosis" type="button" role="tab" aria-controls="diagnosis" aria-selected="false">Current Diagnosis</button>
                                <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos" type="button" role="tab" aria-controls="photos" aria-selected="false">Photos</button>
                            </div>
                        </nav>
                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="tooth-tabContent">
                            <div class="tab-pane fade show active" id="treatment" role="tabpanel" aria-labelledby="treatment-tab">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-date">
                                            <i class="bi bi-check-lg"></i>
                                        </div>
                                        <div class="card mb-0">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1">Composite Filling</h6>
                                                <p class="text-muted small mb-1">March 15, 2024</p>
                                                <p class="mb-0 small">Resin composite filling applied to treat decay on the chewing surface.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-date">
                                            <i class="bi bi-check-lg"></i>
                                        </div>
                                        <div class="card mb-0">
                                            <div class="card-body p-3">
                                                <h6 class="mb-1">Regular Checkup</h6>
                                                <p class="text-muted small mb-1">September 10, 2023</p>
                                                <p class="mb-0 small">Dental examination revealed early signs of decay on occlusal surface.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="diagnosis" role="tabpanel" aria-labelledby="diagnosis-tab">
                                <div class="mb-3">
                                    <h6>Current Status</h6>
                                    <p>Treated - Composite filling in good condition</p>
                                </div>
                                <div class="mb-3">
                                    <h6>Diagnosis Notes</h6>
                                    <p>Tooth has been successfully treated with a resin composite filling for occlusal decay. No current signs of secondary decay or issues with the restoration.</p>
                                </div>
                                <div>
                                    <h6>Recommendations</h6>
                                    <p>Continue regular oral hygiene practices. No additional treatment needed at this time.</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                                <div class="text-center py-4">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No dental photos available</h5>
                                    <p class="text-muted">Dental images will be uploaded after your next appointment</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Toggle between adult and child dental charts
        document.getElementById('adult-chart').addEventListener('change', function() {
            document.getElementById('adult-tooth-chart').style.display = 'block';
            document.getElementById('child-tooth-chart').style.display = 'none';
        });
        
        document.getElementById('child-chart').addEventListener('change', function() {
            document.getElementById('adult-tooth-chart').style.display = 'none';
            document.getElementById('child-tooth-chart').style.display = 'block';
        });
        
        // Handle tooth click to show details
        const teeth = document.querySelectorAll('.tooth');
        teeth.forEach(tooth => {
            tooth.addEventListener('click', function() {
                const toothNum = this.getAttribute('data-tooth-num');
                const toothTitle = this.getAttribute('data-toggle');
                const toothClass = this.className.replace('tooth ', '');
                
                // Update selected tooth details
                document.getElementById('noToothSelected').style.display = 'none';
                document.getElementById('toothDetails').style.display = 'block';
                
                document.getElementById('toothNumber').textContent = toothNum;
                document.getElementById('toothName').textContent = this.getAttribute('title').split(' - ')[0];
                
                // Set quadrant information
                let quadrant = '';
                if (toothNum >= 11 && toothNum <= 18) quadrant = '1';
                else if (toothNum >= 21 && toothNum <= 28) quadrant = '2';
                else if (toothNum >= 31 && toothNum <= 38) quadrant = '3';
                else if (toothNum >= 41 && toothNum <= 48) quadrant = '4';
                else if (toothNum >= 51 && toothNum <= 55) quadrant = '5';
                else if (toothNum >= 61 && toothNum <= 65) quadrant = '6';
                else if (toothNum >= 71 && toothNum <= 75) quadrant = '7';
                else if (toothNum >= 81 && toothNum <= 85) quadrant = '8';
                
                document.getElementById('toothQuadrant').textContent = quadrant;
                
                // Update visual representation
                const selectedToothVisual = document.getElementById('selectedToothVisual');
                selectedToothVisual.className = `tooth ${toothClass} mx-auto`;
                selectedToothVisual.textContent = toothNum;
                
                // Update status
                document.getElementById('toothStatus').textContent = toothClass.charAt(0).toUpperCase() + toothClass.slice(1);
                
                // Set the status indicator color
                const statusIndicator = document.getElementById('toothStatusIndicator');
                statusIndicator.className = 'tooth-status-indicator';
                statusIndicator.classList.add(toothClass);
                
                // Set last treatment info based on tooth status
                const lastTreatmentInfo = document.getElementById('lastTreatmentInfo');
                const noTreatmentInfo = document.getElementById('noTreatmentInfo');
                
                if (toothClass === 'treated') {
                    lastTreatmentInfo.style.display = 'block';
                    noTreatmentInfo.style.display = 'none';
                    document.getElementById('lastTreatmentDate').textContent = 'Date: March 15, 2024';
                    document.getElementById('lastTreatmentType').textContent = 'Procedure: Composite Filling';
                    document.getElementById('lastTreatmentDentist').textContent = 'Dentist: Dr. John Doe';
                } else if (toothClass === 'problem' || toothClass === 'needs-attention') {
                    lastTreatmentInfo.style.display = 'block';
                    noTreatmentInfo.style.display = 'none';
                    document.getElementById('lastTreatmentDate').textContent = 'Date: April 5, 2024';
                    document.getElementById('lastTreatmentType').textContent = 'Diagnosis: Early signs of decay';
                    document.getElementById('lastTreatmentDentist').textContent = 'Dentist: Dr. Jane Smith';
                } else {
                    lastTreatmentInfo.style.display = 'none';
                    noTreatmentInfo.style.display = 'block';
                }
                
                // Set recommendations based on tooth status
                const recommendations = document.getElementById('toothRecommendations');
                if (toothClass === 'healthy') {
                    recommendations.textContent = 'Continue with regular oral hygiene practices and dental check-ups.';
                } else if (toothClass === 'treated') {
                    recommendations.textContent = 'Monitor the treated area. Schedule a follow-up appointment in 6 months.';
                } else if (toothClass === 'needs-attention') {
                    recommendations.textContent = 'Treatment recommended. Please schedule an appointment soon to prevent further issues.';
                } else if (toothClass === 'problem') {
                    recommendations.textContent = 'Requires immediate attention. Please schedule an appointment as soon as possible.';
                } else if (toothClass === 'missing') {
                    recommendations.textContent = 'Consider discussing replacement options with your dentist, such as implants or bridges.';
                }
                
                // Modal details
                document.getElementById('modalToothNumber').textContent = toothNum;
                document.getElementById('modalToothName').textContent = this.getAttribute('title').split(' - ')[0];
                
                const modalTooth = document.getElementById('modalToothDisplay');
                modalTooth.className = `tooth ${toothClass} mx-auto modal-tooth`;
                modalTooth.textContent = toothNum;
            });
        });
        
        // View treatment history button click
        document.getElementById('viewTreatmentHistory').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('toothDetailModal'));
            modal.show();
        });
        
        // Print dental chart
        document.getElementById('printDentalChart').addEventListener('click', function() {
            window.print();
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Dental Chart Specific Styling */
    .dental-chart-container {
        padding: 20px;
    }
    
    .tooth-chart {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 10px;
    }
    
    .tooth {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #ccc;
    }
    
    .tooth:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }
    
    .tooth.healthy {
        background-color: #e8f5e9;
        border-color: #81c784;
    }
    
    .tooth.treated {
        background-color: #e3f2fd;
        border-color: #64b5f6;
    }
    
    .tooth.needs-attention {
        background-color: #fff3e0;
        border-color: #ffb74d;
    }
    
    .tooth.problem {
        background-color: #ffebee;
        border-color: #e57373;
    }
    
    .tooth.missing {
        background-color: #f5f5f5;
        border: 1px dashed #adb5bd;
        color: #adb5bd;
    }
    
    .modal-tooth {
        width: 80px;
        height: 80px;
        font-size: 1.5rem;
    }
    
    .tooth-status-indicator {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .tooth-status-indicator.healthy {
        background-color: #81c784;
    }
    
    .tooth-status-indicator.treated {
        background-color: #64b5f6;
    }
    
    .tooth-status-indicator.needs-attention {
        background-color: #ffb74d;
    }
    
    .tooth-status-indicator.problem {
        background-color: #e57373;
    }
    
    .tooth-status-indicator.missing {
        background-color: #adb5bd;
    }
    
    .tooth-quadrant-badge {
        background-color: #1976d2;
        width: 24px;
        height: 24px;
    }
    
    @media (max-width: 768px) {
        .tooth-chart {
            gap: 5px;
        }
        
        .dental-chart-container {
            padding: 10px;
        }
    }
    
    @media print {
        .sidebar, .header, .page-title, .btn, nav.nav-tabs {
            display: none !important;
        }
        
        .content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush