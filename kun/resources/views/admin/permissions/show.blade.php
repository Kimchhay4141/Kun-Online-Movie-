@extends('layouts.admin')

@section('title', $permission->name . ' - Permission')

@section('content')
<div class="user-form-page">
    <a href="{{ route('admin.permissions.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Permissions</a>
    <div class="dashboard-header">
        <div>
            <h1 class="page-title"><i class="fas fa-shield-alt"></i> {{ $permission->name }}</h1>
            <p class="page-subtitle">{{ $permission->slug }} · created {{ $permission->created_at->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn-add"><i class="fas fa-pen"></i> Edit Permission</a>
    </div>

    <div class="user-form">
        <div class="form-grid">
            <div class="field">
                <label>Module</label>
                <p>{{ $permission->group ?: 'General' }}</p>
            </div>
            <div class="field">
                <label>Assigned roles</label>
                <p>{{ $permission->roles->count() }}</p>
            </div>
            <div class="field full">
                <label>Description</label>
                <p>{{ $permission->description ?: 'No description' }}</p>
            </div>
        </div>
        @if($permission->roles->count())
        <h3 class="section-label">Roles</h3>
        <div class="pill-row">
            @foreach($permission->roles as $role)
            <a class="pill" href="{{ route('admin.roles.show', $role) }}">{{ $role->name }}</a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@include('admin.partials.rbac-form-styles')
<style>
.field p { margin: 0; color: #fff; }
.pill-row { display: flex; flex-wrap: wrap; gap: .5rem; }
.pill { background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; padding: .35rem .75rem; border-radius: 999px; font-size: .8rem; text-decoration: none; }
</style>
@endsection
