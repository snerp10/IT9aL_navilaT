@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #16726d;
        --secondary-color: #d5dfaf;
        --dark-color: #202020;
    }
    
    .auth-container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    
    .auth-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .auth-header {
        background-color: var(--primary-color);
        color: white;
        padding: 1.5rem;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
    }
    
    .auth-body {
        padding: 2rem;
        background-color: white;
    }
    
    .form-control {
        border-radius: 8px;
        padding: 0.75rem;
        border: 1px solid #e0e0e0;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(22, 114, 109, 0.25);
    }
    
    .btn-auth {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-auth:hover {
        background-color: #0f5854;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(22, 114, 109, 0.3);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
    }
    
    .auth-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }
    
    .auth-link:hover {
        text-decoration: underline;
    }
    
    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 12px;
        color: #aaa;
        z-index: 10;
    }
    
    .icon-input {
        padding-left: 40px;
    }
    
    .auth-title {
        margin-bottom: 1.5rem;
        color: var(--dark-color);
        font-weight: bold;
        text-align: center;
    }
    
    .divider-text {
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
    }
    
    .divider-text:before,
    .divider-text:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 45%;
        height: 1px;
        background-color: #e0e0e0;
    }
    
    .divider-text:before {
        left: 0;
    }
    
    .divider-text:after {
        right: 0;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .forgot-password {
        text-align: right;
        margin-top: -0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .welcome-image {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .welcome-image i {
        font-size: 4rem;
        color: var(--primary-color);
    }
</style>

<div class="container auth-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card auth-card">
                <div class="auth-header">
                    {{ __('Welcome Back') }}
                </div>
                
                <div class="auth-body">
                    <div class="welcome-image">
                        <i class="bi bi-hospital"></i>
                    </div>
                    
                    <h4 class="auth-title">Sign In to Your Account</h4>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="input-group">
                            <span class="input-icon"><i class="bi bi-envelope-fill"></i></span>
                            <input id="email" type="email" class="form-control icon-input @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                                placeholder="Email Address">
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group">
                            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                            <input id="password" type="password" class="form-control icon-input @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password" 
                                placeholder="Password">
                            
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a class="auth-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-auth">
                                {{ __('Sign In') }}
                            </button>
                        </div>
                    </form>
                    
                    <div class="divider-text">
                        <span class="px-2 bg-white">or</span>
                    </div>
                    
                    <div class="auth-footer">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="auth-link">Register Now</a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted">
                <small>Dental Clinic Management System © {{ date('Y') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
