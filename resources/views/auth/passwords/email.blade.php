@extends('layouts.app')

@section('styles')
<style>
    .btn-lg {
        padding: 15px 30px;
        font-size: 1.1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @if (session('resetLink'))
                            <div class="d-grid gap-2 col-4 mx-auto mt-3">
                                <a href="{{ session('resetLink') }}" class="btn btn-primary btn-small">
                                    <i class="fas fa-key me-2"></i>Reset Your Password
                                </a>
                                <small class="text-muted text-center mt-2">
                                    Click the button above to set your new password
                                </small>
                            </div>
                        @endif
                    @endif

                    @if (!session('resetLink'))
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
