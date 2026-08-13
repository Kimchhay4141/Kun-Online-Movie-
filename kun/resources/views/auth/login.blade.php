@extends('layouts.app')

@section('title', 'Login - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-film"></i>
                <span>KUN</span>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to continue watching</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Email Address
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       placeholder="Enter your email"
                       required 
                       autofocus>
                @error('email')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <div class="password-input">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password"
                           required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-divider">
            <span>or continue with</span>
        </div>

        <div class="social-login">
            <a href="{{ route('social.login', 'google') }}" class="social-btn google">
                <i class="fab fa-google"></i>
                <span>Google</span>
            </a>
            <a href="{{ route('social.login', 'facebook') }}" class="social-btn facebook">
                <i class="fab fa-facebook-f"></i>
                <span>Facebook</span>
            </a>
        </div>

        <div class="auth-footer">
            <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
        </div>
    </div>

    <div class="auth-background">
        <div class="bg-overlay"></div>
        <div class="bg-pattern"></div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.toggle-password i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.classList.remove('fa-eye');
        toggleBtn.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleBtn.classList.remove('fa-eye-slash');
        toggleBtn.classList.add('fa-eye');
    }
}
</script>
@endsection
