@extends('layouts.receptionist')

@section('title', 'Patient Search Results')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Search Results for "{{ $query }}"</h5>
                <div>
                    <a href="{{ route('receptionist.patient-search') }}" class="btn btn-secondary">
                        <i class="fas fa-search me-1"></i> New Search
                    </a>
                    <a href="{{ route('receptionist.patients.create') }}" class="btn btn-primary ms-2">
                        <i class="fas fa-plus me-1"></i> Add New Patient
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form action="{{ route('receptionist.search-patient') }}" method="POST" class="d-flex">
                            @csrf
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="query" class="form-control" placeholder="Search patients..." value="{{ $query }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($patients->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Contact Info</th>
                                    <th>Age/Gender</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0">{{ $patient->first_name }} {{ $patient->middle_name ? $patient->middle_name . ' ' : '' }}{{ $patient->last_name }}</h6>
                                                <p class="text-muted small mb-0">ID: {{ $patient->patient_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">{{ $patient->contact_number }}</p>
                                        <p class="text-muted small mb-0">{{ $patient->email }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($patient->birth_date)->age }} years</p>
                                        <p class="text-muted small mb-0">{{ ucfirst($patient->gender) }}</p>
                                    </td>
                                    <td>
                                        @if($patient->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('receptionist.patients.show', $patient) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('receptionist.patients.edit', $patient) }}" class="btn btn-sm btn-outline-primary ms-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="btn-group ms-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    More
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('receptionist.appointments.create', ['patient_id' => $patient->patient_id]) }}">
                                                            <i class="fas fa-calendar-plus me-2"></i> New Appointment
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('receptionist.appointments.patient-history', $patient) }}">
                                                            <i class="fas fa-history me-2"></i> Appointment History
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('receptionist.billing.patient', $patient) }}">
                                                            <i class="fas fa-file-invoice-dollar me-2"></i> Billing Records
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $patients->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-search fa-4x text-muted"></i>
                        </div>
                        <h5>No patients found for "{{ $query }}"</h5>
                        <p class="text-muted">
                            Try a different search term or <a href="{{ route('receptionist.patients.create') }}">add a new patient</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ensure Bootstrap dropdowns work properly 
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all dropdowns
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
        dropdownElementList.forEach(function(dropdownToggleEl) {
            // Make sure dropdowns are properly initialized
            dropdownToggleEl.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdownMenu = this.nextElementSibling;
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                } else {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                        openMenu.classList.remove('show');
                    });
                    dropdownMenu.classList.add('show');
                }
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.dropdown-toggle')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                    openMenu.classList.remove('show');
                });
            }
        });
    });
</script>
@endpush
@endsection