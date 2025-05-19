@extends('layouts.receptionist')

@section('title', 'Search Patients')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Search Patients</h5>
                <div>
                    <a href="{{ route('receptionist.patients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-1"></i> All Patients
                    </a>
                    <a href="{{ route('receptionist.patients.create') }}" class="btn btn-primary ms-2">
                        <i class="fas fa-plus me-1"></i> Add New Patient
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form action="{{ route('receptionist.search-patient') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-lg mb-3">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="query" placeholder="Search by name, email, or phone number..." required>
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                            <div class="text-muted small text-center">
                                Enter at least 3 characters to search for patients
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <div class="mb-3">
                        <i class="fas fa-users fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Use the search box above to find patients</h5>
                    <p class="text-muted">
                        You can search by patient name, email address, or phone number
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection