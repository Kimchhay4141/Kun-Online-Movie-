@extends('layouts.admin')

@section('title', $role->name . ' - Role')

@section('content')
<div class="user-form-page">
    <a href="{{ route('admin.roles.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Roles</a>
    <div class="dashboard-header">
        <div>
            <h1 class="page-title"><i class="fas fa-user-tag"></i> {{ $role->name }}</h1>
            <p class="page-subtitle">{{ $role->description ?: $role->slug }} · created {{ $role->created_at->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn-add"><i class="fas fa-pen"></i> Edit Role</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $role->permissions->count() }}</div>
                <div class="stat-label">Permissions</div>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $role->users->count() }}</div>
                <div class="stat-label">Users</div>
            </div>
        </div>
    </div>

    <div class="user-form">
        <h3 class="section-label">Permissions</h3>
        @forelse($permissionsGrouped as $group => $items)
            <p class="group-title">{{ $group ?: 'General' }}</p>
            <div class="pill-row">
                @foreach($items as $permission)
                <span class="pill">{{ $permission->name }}</span>
                @endforeach
            </div>
        @empty
            <p class="muted">This role has no permissions.</p>
        @endforelse
    </div>
</div>
@include('admin.partials.rbac-form-styles')
<style>
.pill-row { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
.pill { background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; padding: .35rem .75rem; border-radius: 999px; font-size: .8rem; }
.group-title { color: var(--text-muted); font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; margin: 0 0 .5rem; }
.muted { color: var(--text-muted); }
</style>
@endsection
