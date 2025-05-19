@extends('layouts.app')

@section('title', 'Dental Clinic Management System')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                <h1 class="display-4 fw-bold mb-3" style="color:#1976d2;">Smile Brighter with Our Expert Dental Care</h1>
                <p class="lead mb-4">Modern solutions for patients and clinics. Manage appointments, records, and more with ease.</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Sign Up Now</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://via.placeholder.com/400x300?text=Dental+Clinic" alt="Dental Clinic" class="img-fluid rounded shadow">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="container py-5">
        <h2 class="text-center mb-4" style="color:#1976d2;">Our Services</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="https://via.placeholder.com/80?text=Tooth" alt="General Dentistry" class="mb-3">
                        <h5 class="card-title">General Dentistry</h5>
                        <p class="card-text">Comprehensive exams, cleanings, and preventive care for all ages.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="https://via.placeholder.com/80?text=Braces" alt="Orthodontics" class="mb-3">
                        <h5 class="card-title">Orthodontics</h5>
                        <p class="card-text">Braces and aligners to help you achieve a perfect smile.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <img src="https://via.placeholder.com/80?text=Whitening" alt="Teeth Whitening" class="mb-3">
                        <h5 class="card-title">Teeth Whitening</h5>
                        <p class="card-text">Safe and effective whitening treatments for a radiant smile.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="container py-5">
        <h2 class="text-center mb-4" style="color:#1976d2;">Why Choose Our System?</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-4 text-center">
                <div class="mb-2">
                    <img src="https://via.placeholder.com/60?text=Easy" alt="Easy Booking">
                </div>
                <h5>Easy Booking</h5>
                <p>Book appointments online anytime, anywhere.</p>
            </div>
            <div class="col-12 col-md-4 text-center">
                <div class="mb-2">
                    <img src="https://via.placeholder.com/60?text=Secure" alt="Secure Records">
                </div>
                <h5>Secure Records</h5>
                <p>Your health data is protected with top-level security.</p>
            </div>
            <div class="col-12 col-md-4 text-center">
                <div class="mb-2">
                    <img src="https://via.placeholder.com/60?text=Trust" alt="Trusted Professionals">
                </div>
                <h5>Trusted Professionals</h5>
                <p>Our team is dedicated to your dental health and comfort.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section (Optional) -->
    <section class="container py-5">
        <h2 class="text-center mb-4" style="color:#1976d2;">What Our Patients Say</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="card-text">"Booking my appointment was so easy and the staff is wonderful!"</p>
                        <div class="fw-bold">- Jane D.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="card-text">"I love how secure and organized my records are now."</p>
                        <div class="fw-bold">- Mark S.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light border-top py-4 mt-5">
        <div class="container text-center">
            <div class="mb-2">
                <a href="{{ route('login') }}" class="text-decoration-none me-3">Login</a>
                <a href="#" class="text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-decoration-none">About Us</a>
            </div>
            <div class="text-muted small">&copy; {{ date('Y') }} Dental Clinic Management System. All rights reserved.</div>
            <div class="text-muted small">Contact: info@dentalclinic.com | (123) 456-7890</div>
        </div>
    </footer>
</div>
@endsection
