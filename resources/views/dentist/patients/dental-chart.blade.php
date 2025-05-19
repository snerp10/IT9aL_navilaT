@extends('layouts.dentist')

@section('title', 'Dental Chart')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dentist.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentist.patients.index') }}">Patients</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentist.patients.show', $patient->patient_id) }}">{{ $patient->first_name }} {{ $patient->last_name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dental Chart</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Dental Chart: {{ $patient->first_name }} {{ $patient->last_name }}</h1>
                <a href="{{ route('dentist.treatments.create', ['patient_id' => $patient->patient_id]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> New Treatment
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Adult Teeth Chart</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active" data-chart-view="adult">Adult</button>
                        <button type="button" class="btn btn-outline-primary" data-chart-view="child">Child</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="dental-chart-container">
                        <!-- Upper Teeth -->
                        <div class="row justify-content-center mb-5 upper-teeth">
                            <div class="col-md-10">
                                <div class="row">
                                    @for ($i = 18; $i >= 11; $i--)
                                        <div class="col text-center mb-3">
                                            <div class="tooth-wrapper position-relative"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="{{ isset($treatments[$i]) ? $treatments[$i]->first()->name : 'No treatment' }}">
                                                <div class="tooth {{ isset($treatments[$i]) ? 'treated' : '' }}" data-tooth-number="{{ $i }}">
                                                    {{ $i }}
                                                </div>
                                                @if(isset($treatments[$i]))
                                                    <div class="treatment-marker"></div>
                                                @endif
                                            </div>
                                            <small class="tooth-number">{{ $i }}</small>
                                        </div>
                                    @endfor
                                    @for ($i = 21; $i <= 28; $i++)
                                        <div class="col text-center mb-3">
                                            <div class="tooth-wrapper position-relative"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="{{ isset($treatments[$i]) ? $treatments[$i]->first()->name : 'No treatment' }}">
                                                <div class="tooth {{ isset($treatments[$i]) ? 'treated' : '' }}" data-tooth-number="{{ $i }}">
                                                    {{ $i }}
                                                </div>
                                                @if(isset($treatments[$i]))
                                                    <div class="treatment-marker"></div>
                                                @endif
                                            </div>
                                            <small class="tooth-number">{{ $i }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lower Teeth -->
                        <div class="row justify-content-center lower-teeth">
                            <div class="col-md-10">
                                <div class="row">
                                    @for ($i = 48; $i >= 41; $i--)
                                        <div class="col text-center mb-3">
                                            <div class="tooth-wrapper position-relative"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="bottom" 
                                                title="{{ isset($treatments[$i]) ? $treatments[$i]->first()->name : 'No treatment' }}">
                                                <div class="tooth {{ isset($treatments[$i]) ? 'treated' : '' }}" data-tooth-number="{{ $i }}">
                                                    {{ $i }}
                                                </div>
                                                @if(isset($treatments[$i]))
                                                    <div class="treatment-marker"></div>
                                                @endif
                                            </div>
                                            <small class="tooth-number">{{ $i }}</small>
                                        </div>
                                    @endfor
                                    @for ($i = 31; $i <= 38; $i++)
                                        <div class="col text-center mb-3">
                                            <div class="tooth-wrapper position-relative"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="bottom" 
                                                title="{{ isset($treatments[$i]) ? $treatments[$i]->first()->name : 'No treatment' }}">
                                                <div class="tooth {{ isset($treatments[$i]) ? 'treated' : '' }}" data-tooth-number="{{ $i }}">
                                                    {{ $i }}
                                                </div>
                                                @if(isset($treatments[$i]))
                                                    <div class="treatment-marker"></div>
                                                @endif
                                            </div>
                                            <small class="tooth-number">{{ $i }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Treatment History by Tooth</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tooth Number</th>
                                    <th>Date</th>
                                    <th>Treatment</th>
                                    <th>Description</th>
                                    <th>Dentist</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($treatments) > 0)
                                    @foreach($treatments as $toothNumber => $toothTreatments)
                                        @foreach($toothTreatments as $treatment)
                                            <tr>
                                                <td><span class="badge bg-primary">{{ $treatment->tooth_number }}</span></td>
                                                <td>{{ \Carbon\Carbon::parse($treatment->created_at)->format('M d, Y') }}</td>
                                                <td>{{ $treatment->name }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($treatment->description, 100) }}</td>
                                                <td>Dr. {{ $treatment->dentist->first_name ?? '' }} {{ $treatment->dentist->last_name ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">No dental treatment records found</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Treatment Details Modal -->
<div class="modal fade" id="treatmentDetailsModal" tabindex="-1" aria-labelledby="treatmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="treatmentDetailsModalLabel">Treatment Details: Tooth <span id="modal-tooth-number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="treatment-details-content">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading treatment details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="new-tooth-treatment-btn">
                    <i class="bi bi-plus-circle me-1"></i> New Treatment for This Tooth
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dental-chart-container {
        background-color: #f8f9fa;
        border-radius: 1rem;
        padding: 2rem;
        position: relative;
    }
    
    .tooth-wrapper {
        display: inline-block;
    }
    
    .tooth {
        width: 40px;
        height: 50px;
        border: 2px solid #dee2e6;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: bold;
        color: #495057;
    }
    
    .tooth:hover {
        background-color: #e9ecef;
        transform: translateY(-3px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .tooth.treated {
        background-color: #e6f3ff;
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .treatment-marker {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 12px;
        height: 12px;
        background-color: #dc3545;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .tooth-number {
        display: block;
        margin-top: 0.25rem;
        color: #6c757d;
        font-size: 0.7rem;
    }
    
    .upper-teeth .tooth {
        border-radius: 5px 5px 15px 15px;
    }
    
    .lower-teeth .tooth {
        border-radius: 15px 15px 5px 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Handle tooth click to show treatment details
        document.querySelectorAll('.tooth').forEach(tooth => {
            tooth.addEventListener('click', function() {
                const toothNumber = this.dataset.toothNumber;
                showToothTreatments(toothNumber);
            });
        });
        
        function showToothTreatments(toothNumber) {
            // Set the tooth number in the modal
            document.getElementById('modal-tooth-number').textContent = toothNumber;
            
            // Set the new treatment link
            document.getElementById('new-tooth-treatment-btn').href = 
                `{{ url('/dentist/treatments/create') }}?patient_id={{ $patient->patient_id }}&tooth_number=${toothNumber}`;
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('treatmentDetailsModal'));
            modal.show();
            
            // Load treatment details for this tooth
            const detailsContainer = document.getElementById('treatment-details-content');
            
            // Check if there are treatments for this tooth
            @foreach($treatments as $toothNum => $toothTreatments)
                if (toothNumber == {{ $toothNum }}) {
                    let html = '<div class="list-group">';
                    
                    @foreach($toothTreatments as $treatment)
                        html += `
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $treatment->name }}</h5>
                                    <small>{{ \Carbon\Carbon::parse($treatment->created_at)->format('M d, Y') }}</small>
                                </div>
                                <p class="mb-1">{{ $treatment->description }}</p>
                                <small class="text-muted">Dr. {{ $treatment->dentist->first_name ?? '' }} {{ $treatment->dentist->last_name ?? '' }}</small>
                                <div class="mt-2">
                                    <a href="{{ route('dentist.treatments.show', $treatment->treatment_id) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        `;
                    @endforeach
                    
                    html += '</div>';
                    detailsContainer.innerHTML = html;
                    return;
                }
            @endforeach
            
            // No treatments found
            detailsContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-info-circle text-primary" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">No treatment records found for tooth #${toothNumber}.</p>
                </div>
            `;
        }
        
        // Toggle between adult and child teeth views
        document.querySelectorAll('[data-chart-view]').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('[data-chart-view]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // TODO: Implement switching between adult and child views
                // This would require different tooth sets for children
            });
        });
    });
</script>
@endpush