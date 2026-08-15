@extends('layouts.admin')

@section('title', 'Edit Role - Admin')

@section('content')
<div class="user-form-page">
    <a href="{{ route('admin.roles.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Roles</a>
    <h1 class="page-title"><i class="fas fa-user-edit"></i> Edit Role</h1>
    <p class="page-subtitle">Update {{ $role->name }} · created {{ $role->created_at->format('M d, Y') }}</p>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="user-form">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Role name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}" required>
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label>Slug</label>
                <input type="text" value="{{ $role->slug }}" disabled>
            </div>
            <div class="field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
            </div>
        </div>

        <h3 class="section-label">Permissions</h3>
        @foreach($permissions as $group => $groupPermissions)
        <div class="perm-group">
            <h4>{{ $group ?: 'General' }}</h4>
            <div class="perm-grid">
                @foreach($groupPermissions as $permission)
                <label class="check">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                    <span>
                        <strong>{{ $permission->name }}</strong>
                        <small>{{ $permission->slug }}</small>
                    </span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn-add">Save Changes</button>
            <a href="{{ route('admin.roles.index') }}" class="btn-reset">Cancel</a>
        </div>
    </form>
</div>
@include('admin.partials.rbac-form-styles')
@endsection
