@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="fas fa-arrow-left me-2"></i>Back to Roles
            </a>
        </div>
        <h1 class="h3 mb-0 mt-2">Edit Role: {{ $role->name }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $role->name) }}"
                                   placeholder="Enter role name"
                                   required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Enter role description">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block mb-3">Assign Permissions</label>
                            
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="permission-group mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-folder me-2"></i>{{ $group }}
                                </h6>
                                <div class="row">
                                    @foreach($groupPermissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}" 
                                                   id="permission-{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                <strong>{{ $permission->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $permission->description }}</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            @endforeach

                            @error('permissions')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role Info Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Role Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Slug</small>
                        <code>{{ $role->slug }}</code>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Users with this role</small>
                        <strong>{{ $role->users()->count() }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Created</small>
                        <strong>{{ $role->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Last Updated</small>
                        <strong>{{ $role->updated_at->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permission-group {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border-left: 4px solid #0d6efd;
}

.form-check {
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.form-check:hover {
    background-color: #e9ecef;
}

.form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
}

code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    color: #0d6efd;
}
</style>
@endsection
