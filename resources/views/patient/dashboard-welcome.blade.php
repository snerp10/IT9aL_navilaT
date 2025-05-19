@extends('layouts.patient')

@section('title', 'Welcome to Patient Portal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Welcome to the Patient Portal</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle me-2"></i>Your account setup is incomplete</h5>
                        <p>Thank you for logging in to our patient portal. It appears that your patient record is not yet fully set up in our system.</p>
                        <p>To access all patient features like appointments, treatment history, and billing, please contact our reception desk to complete your patient profile.</p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Contact Information</h5>
                                    <div class="mt-3">
                                        <p><i class="bi bi-telephone me-2"></i> Phone: (123) 456-7890</p>
                                        <p><i class="bi bi-envelope me-2"></i> Email: reception@dentalclinic.com</p>
                                        <p><i class="bi bi-geo-alt me-2"></i> Address: 123 Dental Street, City, State 12345</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Your Account Information</h5>
                                    <div class="mt-3">
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Account Created:</strong> {{ $user->created_at->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p>If you believe this is an error, please contact our support team.</p>
                        <a href="{{ route('patient.complete-profile') }}" class="btn btn-success">
                            <i class="bi bi-person-lines-fill me-2"></i> Complete My Profile
                        </a>
                        <a href="#" class="btn btn-primary ms-2" onclick="alert('Please call our reception desk to schedule your appointment.')">
                            <i class="bi bi-calendar-plus me-2"></i> Schedule an Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection