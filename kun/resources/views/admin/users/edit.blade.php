@extends('layouts.admin')

@section('title', 'Edit User - Admin')

@section('content')
<div class="user-form-page">
    <a href="{{ route('admin.users.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Users</a>
    <h1 class="page-title"><i class="fas fa-user-edit"></i> Edit User</h1>
    <p class="page-subtitle">Update {{ $user->name }}'s account details</p>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="user-form">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}">
                @error('phone')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="roles">Role</label>
                <select id="roles" name="roles[]">
                    <option value="">Select role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoles)) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('roles')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">New password</label>
                <input id="password" name="password" type="password" placeholder="Leave blank to keep current">
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password">
            </div>
            <div class="field">
                <label for="subscription_status">Status</label>
                <select id="subscription_status" name="subscription_status">
                    @foreach(['active', 'inactive', 'suspended'] as $status)
                    <option value="{{ $status }}" {{ old('subscription_status', $user->subscription_status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-add">Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="btn-reset">Cancel</a>
        </div>
    </form>
</div>

<style>
.back-link { display: inline-flex; align-items: center; gap: .5rem; color: var(--text-secondary); text-decoration: none; margin-bottom: 1rem; }
.back-link:hover { color: #fff; }
.user-form { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.75rem; margin-top: 1.25rem; max-width: 860px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
.field { display: flex; flex-direction: column; gap: .45rem; }
.field label { font-weight: 600; font-size: .85rem; color: var(--text-secondary); }
.field input, .field select { background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; border-radius: 10px; padding: .75rem .9rem; }
.field input:focus, .field select:focus { outline: none; border-color: var(--primary-color); }
.error { color: var(--danger-color); font-size: .8rem; }
.form-actions { display: flex; gap: .75rem; margin-top: 1.5rem; }
.btn-add { display: inline-flex; align-items: center; padding: .7rem 1.25rem; background: var(--primary-color); color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; text-decoration: none; }
.btn-reset { display: inline-flex; align-items: center; padding: .7rem 1.1rem; background: var(--light-bg); color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 10px; text-decoration: none; font-weight: 600; }
@media (max-width: 700px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection
