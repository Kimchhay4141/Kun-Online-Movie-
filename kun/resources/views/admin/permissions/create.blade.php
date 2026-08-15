@extends('layouts.admin')

@section('title', 'Add Permission - Admin')

@section('content')
<div class="user-form-page">
    <a href="{{ route('admin.permissions.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Permissions</a>
    <h1 class="page-title"><i class="fas fa-plus"></i> Add Permission</h1>
    <p class="page-subtitle">Create a permission and group it by module</p>

    <form action="{{ route('admin.permissions.store') }}" method="POST" class="user-form">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="slug">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug') }}" placeholder="e.g. movie.create" required>
                @error('slug')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="group">Module</label>
                <input id="group" name="group" type="text" list="modules" value="{{ old('group') }}" required>
                <datalist id="modules">
                    @foreach($modules as $module)
                    <option value="{{ $module }}"></option>
                    @endforeach
                </datalist>
                @error('group')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-add">Create Permission</button>
            <a href="{{ route('admin.permissions.index') }}" class="btn-reset">Cancel</a>
        </div>
    </form>
</div>
@include('admin.partials.rbac-form-styles')
@endsection
