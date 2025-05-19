@extends('layouts.receptionist')

@section('title', 'Patient Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Patient Management</h5>
                <div>
                    <a href="{{ route('receptionist.patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> New Patient
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <!-- Status Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Generated Password Message -->
                @if(session('generated_password'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>User account created!</strong> The temporary password is: <code>{{ session('generated_password') }}</code>
                    <p class="mb-0">Please inform the patient to change this password after first login.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form action="{{ route('receptionist.patients.index') }}" method="GET" class="d-flex">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('receptionist.patients.index') }}" method="GET" class="d-flex">
                            <select name="status" class="form-control form-select me-2">
                                <option value="all">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                </div>

                <!-- Patients Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Contact Info</th>
                                <th>Age/Gender</th>
                                <th>Last Visit</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
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
                                    @php
                                        $lastAppointment = $patient->appointments()->latest('appointment_date')->first();
                                    @endphp
                                    @if($lastAppointment)
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('M d, Y') }}</p>
                                    @else
                                        <p class="text-muted small mb-0">No visits yet</p>
                                    @endif
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
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No patients found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $patients->links('vendor.pagination.custom-theme') }}
                </div>
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