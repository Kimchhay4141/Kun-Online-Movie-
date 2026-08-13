@extends('layouts.app')

@section('title', 'My Profile - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')
<div class="profile-page">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=e50914&color=fff&size=200' }}" alt="{{ auth()->user()->name }}">
                <button class="change-avatar-btn">
                    <i class="fas fa-camera"></i>
                </button>
            </div>

            <div class="profile-info">
                <h1>{{ auth()->user()->name }}</h1>
                <p class="profile-email">{{ auth()->user()->email }}</p>
                
                @if(auth()->user()->subscription_plan)
                <div class="subscription-badge {{ auth()->user()->subscription_status }}">
                    <i class="fas fa-crown"></i>
                    {{ ucfirst(auth()->user()->subscription_plan) }} Plan
                </div>
                @endif

                <div class="profile-stats">
                    <div class="stat">
                        <span class="stat-value">{{ auth()->user()->movieViews()->count() }}</span>
                        <span class="stat-label">Watched</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">{{ auth()->user()->favorites()->count() }}</span>
                        <span class="stat-label">Favorites</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">{{ auth()->user()->watchlist()->count() }}</span>
                        <span class="stat-label">Watchlist</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Tabs -->
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="account">
                <i class="fas fa-user"></i>
                Account Details
            </button>
            <button class="tab-btn" data-tab="security">
                <i class="fas fa-lock"></i>
                Security
            </button>
            <button class="tab-btn" data-tab="subscription">
                <i class="fas fa-crown"></i>
                Subscription
            </button>
            <button class="tab-btn" data-tab="preferences">
                <i class="fas fa-cog"></i>
                Preferences
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Account Tab -->
            <div class="tab-pane active" id="account">
                <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}">
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ auth()->user()->date_of_birth }}">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane" id="security">
                <form action="{{ route('profile.password') }}" method="POST" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i>
                        Update Password
                    </button>
                </form>

                <div class="security-options">
                    <h3>Two-Factor Authentication</h3>
                    <p>Add an extra layer of security to your account</p>
                    <button class="btn btn-secondary">
                        <i class="fas fa-shield-alt"></i>
                        Enable 2FA
                    </button>
                </div>
            </div>

            <!-- Subscription Tab -->
            <div class="tab-pane" id="subscription">
                @if(auth()->user()->subscription_plan)
                <div class="current-plan">
                    <h3>Current Plan: {{ ucfirst(auth()->user()->subscription_plan) }}</h3>
                    <p>Status: <span class="badge-{{ auth()->user()->subscription_status }}">{{ ucfirst(auth()->user()->subscription_status) }}</span></p>
                    <p>Expires: {{ auth()->user()->subscription_end ? auth()->user()->subscription_end->format('F j, Y') : 'N/A' }}</p>
                    
                    <div class="subscription-actions">
                        <a href="{{ route('subscription.plans') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-up"></i>
                            Upgrade Plan
                        </a>
                        <button class="btn btn-danger" onclick="cancelSubscription()">
                            <i class="fas fa-times"></i>
                            Cancel Subscription
                        </button>
                    </div>
                </div>
                @else
                <div class="no-subscription">
                    <i class="fas fa-crown"></i>
                    <h3>You don't have an active subscription</h3>
                    <p>Subscribe now to unlock premium features</p>
                    <a href="{{ route('subscription.plans') }}" class="btn btn-primary">
                        <i class="fas fa-star"></i>
                        View Plans
                    </a>
                </div>
                @endif
            </div>

            <!-- Preferences Tab -->
            <div class="tab-pane" id="preferences">
                <form action="{{ route('profile.preferences') }}" method="POST" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="email_notifications" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                            Email Notifications
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="auto_play_next" {{ auth()->user()->auto_play_next ? 'checked' : '' }}>
                            Auto-play Next Episode
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="video_quality">Default Video Quality</label>
                        <select id="video_quality" name="video_quality">
                            <option value="auto">Auto</option>
                            <option value="4K">4K</option>
                            <option value="1080p">1080p</option>
                            <option value="720p">720p</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Preferences
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        
        btn.classList.add('active');
        document.getElementById(tab).classList.add('active');
    });
});

function cancelSubscription() {
    if (confirm('Are you sure you want to cancel your subscription?')) {
        fetch('/subscription/cancel', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
    }
}
</script>
@endsection
