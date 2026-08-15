# 🔐 RBAC System Documentation

## Role-Based Access Control for Online Movie Platform

This document describes the complete RBAC (Role-Based Access Control) system implementation for your online movie streaming platform.

---

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [Database Structure](#database-structure)
3. [Roles & Permissions](#roles--permissions)
4. [Usage Guide](#usage-guide)
5. [API Reference](#api-reference)
6. [Admin Panel Features](#admin-panel-features)

---

## System Overview

The RBAC system provides fine-grained access control for your movie streaming platform with:

✅ **5 Pre-configured Roles**
- Admin (35 permissions)
- Content Manager (12 permissions)
- Moderator (9 permissions)
- User (1 permission)
- Premium User (1 permission)

✅ **35 Permissions** across 11 modules:
- Dashboard, Movies, Genres, Users, Roles, Permissions
- Payments, Moderation, Analytics, Settings

✅ **Features**:
- Create/Edit/Delete Users with role assignment
- Create/Edit/Delete Roles with permission assignment
- Manage Permissions and group them by modules
- Policy-based authorization
- Middleware protection

---

## Database Structure

### Tables

#### `roles`
```sql
- id (primary key)
- name (string, unique)
- slug (string, unique)
- description (text, nullable)
- created_at, updated_at
```

#### `permissions`
```sql
- id (primary key)
- name (string)
- slug (string, unique)
- description (text, nullable)
- group (string) -- Module grouping
- created_at, updated_at
```

#### `role_user` (pivot table)
```sql
- role_id (foreign key)
- user_id (foreign key)
- created_at, updated_at
```

#### `permission_role` (pivot table)
```sql
- permission_id (foreign key)
- role_id (foreign key)
- created_at, updated_at
```

---

## Roles & Permissions

### 1. Admin Role
**Full system access** - Can perform all operations

**Permissions (35)**:
- All Dashboard access
- Full Movie management (read, create, update, delete, publish, videos)
- Full Genre management (read, create, update, delete)
- Full User management (read, create, update, delete, suspend, assign-roles)
- Full Role management (read, create, update, delete)
- Full Permission management (read, create, update, delete)
- Payment management (read, refund, manage subscriptions)
- Content moderation (reviews, comments)
- Analytics & Reports access
- Settings management

### 2. Content Manager Role
**Manages movies and genres**

**Permissions (12)**:
- Access Admin Dashboard
- Full Movie management (read, create, update, delete, publish, videos)
- Full Genre management (read, create, update, delete)
- View Analytics

### 3. Moderator Role
**Moderates content and manages users**

**Permissions (9)**:
- Access Moderator Dashboard
- View & Update Movies
- View Genres
- View Users & Suspend Users
- Moderate Reviews & Comments
- View Analytics

### 4. User Role
**Basic user access**

**Permissions (1)**:
- Access User Dashboard

### 5. Premium User Role
**Premium subscriber access**

**Permissions (1)**:
- Access User Dashboard

---

## Usage Guide

### Seeding Initial Data

Run the seeder to create all roles and permissions:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Creating a User with Role

```php
use App\Models\User;
use App\Models\Role;

$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
]);

// Assign role
$adminRole = Role::findBySlug('admin');
$user->assignRole($adminRole);
```

### Checking Permissions

```php
// Check if user has specific role
if ($user->hasRole('admin')) {
    // User is admin
}

// Check if user has specific permission
if ($user->hasPermission('movie.create')) {
    // User can create movies
}

// Check multiple roles
if ($user->hasAnyRole(['admin', 'moderator'])) {
    // User is either admin or moderator
}

// Check all permissions
if ($user->hasAllPermissions(['movie.create', 'movie.update'])) {
    // User has both permissions
}
```

### Using in Controllers

```php
namespace App\Http\Controllers\Admin;

use App\Models\Movie;

class MovieController extends Controller
{
    public function create()
    {
        // Check authorization using policy
        $this->authorize('create', Movie::class);
        
        return view('admin.movies.create');
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', Movie::class);
        
        // Create movie logic
    }
}
```

### Using in Blade Templates

```blade
@can('create', App\Models\Movie::class)
    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
        Create Movie
    </a>
@endcan

@if(auth()->user()->hasRole('admin'))
    <!-- Admin only content -->
@endif

@if(auth()->user()->hasPermission('movie.delete'))
    <button class="btn btn-danger">Delete</button>
@endif
```

### Using Middleware

```php
// In routes/web.php

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('admin/movies', MovieController::class);
});

Route::middleware(['auth', 'permission:movie.create'])->group(function () {
    Route::post('admin/movies', [MovieController::class, 'store']);
});
```

---

## API Reference

### User Model Methods

```php
// Role Methods
$user->hasRole('admin');                    // Check single role
$user->hasAnyRole(['admin', 'moderator']); // Check multiple roles
$user->hasAllRoles(['admin', 'manager']);  // Check all roles
$user->isAdmin();                          // Quick admin check
$user->isModerator();                      // Quick moderator check
$user->assignRole('admin');                // Assign role
$user->removeRole('admin');                // Remove role
$user->syncRoles(['admin', 'moderator']);  // Sync roles

// Permission Methods
$user->hasPermission('movie.create');                    // Check permission
$user->hasAllPermissions(['movie.create', 'movie.update']); // Check all
$user->permissions();                                     // Get all permissions

// Relationships
$user->roles;          // Get all roles
```

### Role Model Methods

```php
// Permission Methods
$role->hasPermission('movie.create');              // Check permission
$role->assignPermission('movie.create');           // Assign permission
$role->removePermission('movie.create');           // Remove permission
$role->syncPermissions(['movie.create', 'movie.update']); // Sync permissions

// Static Methods
Role::findByName('Admin');      // Find by name
Role::findBySlug('admin');      // Find by slug

// Relationships
$role->permissions;   // Get all permissions
$role->users;        // Get all users
```

### Permission Model Methods

```php
// Static Methods
Permission::findByName('Create Movie');   // Find by name
Permission::findBySlug('movie.create');   // Find by slug
Permission::getAllGrouped();              // Get grouped by module

// Relationships
$permission->roles;   // Get all roles
```

---

## Admin Panel Features

### Users Management

**URL**: `/admin/users`

**Features**:
- ✅ List all users with roles
- ✅ Create new user with role assignment
- ✅ Edit user details and roles
- ✅ Delete users (with protection)
- ✅ Suspend/unsuspend users
- ✅ Filter by role, subscription
- ✅ Search by name, email

**Screenshots Reference**: Create User form (shown in your images)

### Roles Management

**URL**: `/admin/roles`

**Features**:
- ✅ List all roles with permission count
- ✅ Create new role with permission selection
- ✅ Edit role and update permissions
- ✅ Delete roles (with protection)
- ✅ View role details with assigned users
- ✅ Permission grouping by module

**Screenshots Reference**: Roles table (shown in your images)

### Permissions Management

**URL**: `/admin/permissions`

**Features**:
- ✅ List all permissions by module
- ✅ Create new permissions
- ✅ Edit permission details
- ✅ Delete permissions (with protection)
- ✅ Filter by module
- ✅ Search permissions
- ✅ View which roles have each permission

**Screenshots Reference**: Permissions table (shown in your images)

---

## Routes

### User Management Routes
```
GET    /admin/users              - List users
GET    /admin/users/create       - Create user form
POST   /admin/users              - Store user
GET    /admin/users/{id}         - View user
GET    /admin/users/{id}/edit    - Edit user form
PUT    /admin/users/{id}         - Update user
DELETE /admin/users/{id}         - Delete user
```

### Role Management Routes
```
GET    /admin/roles              - List roles
GET    /admin/roles/create       - Create role form
POST   /admin/roles              - Store role
GET    /admin/roles/{role}       - View role
GET    /admin/roles/{role}/edit  - Edit role form
PUT    /admin/roles/{role}       - Update role
DELETE /admin/roles/{role}       - Delete role
```

### Permission Management Routes
```
GET    /admin/permissions              - List permissions
GET    /admin/permissions/create       - Create permission form
POST   /admin/permissions              - Store permission
GET    /admin/permissions/{permission} - View permission
GET    /admin/permissions/{permission}/edit - Edit permission form
PUT    /admin/permissions/{permission} - Update permission
DELETE /admin/permissions/{permission} - Delete permission
```

---

## Security Features

### 1. Policy-Based Authorization
- All CRUD operations protected by policies
- Automatic admin bypass for super-admins

### 2. Protected System Roles
- Cannot delete `admin` or `super-admin` roles
- Cannot delete roles with assigned users
- Cannot delete own user account

### 3. Permission Validation
- Cannot delete permissions assigned to roles
- Validates permission existence before assignment

### 4. Middleware Protection
- `auth` - Requires authentication
- `admin` - Requires admin role
- `role:admin` - Requires specific role
- `permission:movie.create` - Requires specific permission

---

## Testing

### Create Admin User

```bash
php artisan tinker
```

```php
$user = User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'),
]);

$adminRole = Role::findBySlug('admin');
$user->assignRole($adminRole);
```

### Verify Installation

1. Visit: `/admin/roles`
2. You should see 5 roles
3. Click on "Admin" role
4. Should show 35 permissions

---

## Customization

### Add New Permission

```php
Permission::create([
    'name' => 'Manage Comments',
    'slug' => 'comment.manage',
    'description' => 'Can manage user comments',
    'group' => 'Moderation',
]);
```

### Create Custom Role

```php
$role = Role::create([
    'name' => 'Editor',
    'slug' => 'editor',
    'description' => 'Can edit content',
]);

$permissions = Permission::whereIn('slug', [
    'movie.read',
    'movie.update',
    'genre.read',
])->pluck('id');

$role->permissions()->sync($permissions);
```

---

## Troubleshooting

### Permission Denied Errors

1. Check if user has required role/permission
2. Verify policy is registered in `AppServiceProvider`
3. Clear cache: `php artisan cache:clear`

### Roles Not Showing

1. Run seeder: `php artisan db:seed --class=RolePermissionSeeder`
2. Check database connection
3. Verify migrations ran successfully

---

## Support

For issues or questions:
1. Check this documentation
2. Review controller and policy files
3. Check Laravel authorization docs: https://laravel.com/docs/authorization

---

**Last Updated**: August 15, 2026
**Version**: 1.0.0
