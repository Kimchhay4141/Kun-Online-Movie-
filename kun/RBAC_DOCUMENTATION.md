# 🔐 RBAC System Documentation - Kun Movie Platform

## Table of Contents
1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Roles & Permissions](#roles--permissions)
4. [Usage Guide](#usage-guide)
5. [Middleware Usage](#middleware-usage)
6. [Policy Usage](#policy-usage)
7. [Helper Functions](#helper-functions)
8. [Blade Directives](#blade-directives)
9. [Database Seeding](#database-seeding)
10. [Testing Credentials](#testing-credentials)
11. [Best Practices](#best-practices)

---

## Overview

The Kun Movie Platform implements a comprehensive Role-Based Access Control (RBAC) system that provides:

- **5 predefined roles** with hierarchical permissions
- **40+ granular permissions** organized by resource type
- **Middleware** for route protection
- **Policy classes** for model-level authorization
- **Helper functions** for quick access checks
- **Blade directives** for view-level authorization

---

## System Architecture

### Database Tables

```
users
├── roles (many-to-many via role_user)
│   └── permissions (many-to-many via permission_role)
└── Direct relationship to check permissions through roles
```

### Key Components

| Component | Location | Purpose |
|-----------|----------|---------|
| User Model | `app/Models/User.php` | RBAC relationships & helper methods |
| Role Model | `app/Models/Role.php` | Role management |
| Permission Model | `app/Models/Permission.php` | Permission management |
| Middleware | `app/Http/Middleware/` | Route protection |
| Policies | `app/Policies/` | Model authorization |
| Seeders | `database/seeders/` | Initial data setup |
| Helper Functions | `app/helpers.php` | Global RBAC helpers |

---

## Roles & Permissions

### Available Roles

| Role | Slug | Description | Access Level |
|------|------|-------------|--------------|
| **Admin** | `admin` | Full system access | All permissions |
| **Moderator** | `moderator` | Content & user moderation | Movies, Genres, Users (limited), Analytics |
| **Content Manager** | `content-manager` | Movie & genre management | Movies, Genres, Analytics (view) |
| **Support** | `support` | Customer support | View users, payments, subscriptions |
| **User** | `user` | Standard user | View movies & genres |

### Permission Groups

#### 🎬 Movies (`movies.*`)
- `movies.view` - View published movies
- `movies.view-all` - View all movies including drafts
- `movies.create` - Create new movies
- `movies.edit` - Edit existing movies
- `movies.delete` - Delete movies
- `movies.publish` - Publish/unpublish movies
- `movies.manage-videos` - Upload & manage video files

#### 🎭 Genres (`genres.*`)
- `genres.view` - View all genres
- `genres.create` - Create new genres
- `genres.edit` - Edit existing genres
- `genres.delete` - Delete genres

#### 👥 Users (`users.*`)
- `users.view` - View user list & profiles
- `users.create` - Create new users
- `users.edit` - Edit user information
- `users.delete` - Delete users
- `users.manage-roles` - Assign/remove user roles
- `users.ban` - Ban/unban users

#### 💳 Payments (`payments.*`)
- `payments.view` - View payment transactions
- `payments.refund` - Process refunds
- `payments.manage-subscriptions` - Manage subscriptions
- `payments.view-reports` - View financial reports

#### 🔑 Roles (`roles.*`)
- `roles.view` - View all roles
- `roles.create` - Create new roles
- `roles.edit` - Edit existing roles
- `roles.delete` - Delete roles
- `roles.manage-permissions` - Assign permissions to roles

#### 📊 Analytics (`analytics.*`)
- `analytics.view` - View analytics dashboard
- `analytics.export` - Export reports & data

#### ⚙️ Settings (`settings.*`)
- `settings.view` - View system settings
- `settings.edit` - Modify system settings

---

## Usage Guide

### Check User Roles

```php
// In controller or model
if ($user->hasRole('admin')) {
    // User is admin
}

// Check multiple roles (OR logic)
if ($user->hasRole(['admin', 'moderator'])) {
    // User has at least one of these roles
}

// Check if user is specifically admin
if ($user->isAdmin()) {
    // User is admin
}
```

### Check User Permissions

```php
// Check single permission
if ($user->hasPermission('movies.create')) {
    // User can create movies
}

// Check multiple permissions (OR logic)
if ($user->hasAnyPermission(['movies.edit', 'movies.delete'])) {
    // User has at least one of these permissions
}

// Check all permissions (AND logic)
if ($user->hasAllPermissions(['movies.edit', 'movies.publish'])) {
    // User has both permissions
}
```

### Assign Roles to Users

```php
// Assign single role
$user->assignRole('moderator');

// Assign multiple roles
$user->assignRole(['moderator', 'content-manager']);

// Sync roles (removes existing, adds new)
$user->syncRoles(['admin']);

// Remove role
$user->removeRole('moderator');
```

### Manage Role Permissions

```php
$role = Role::where('slug', 'moderator')->first();

// Assign permission
$role->assignPermission('movies.create');

// Sync permissions
$permissions = Permission::whereIn('slug', [
    'movies.view-all',
    'movies.create',
    'movies.edit'
])->pluck('id')->toArray();

$role->syncPermissions($permissions);
```

---

## Middleware Usage

### Available Middleware

| Alias | Class | Usage |
|-------|-------|-------|
| `admin` | `AdminMiddleware` | Requires admin role |
| `role` | `RoleMiddleware` | Requires specific role(s) |
| `permission` | `PermissionMiddleware` | Requires specific permission |

### Protect Routes

```php
// routes/web.php

// Require admin role
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Require specific role
Route::middleware(['auth', 'role:moderator'])->group(function () {
    Route::resource('/admin/movies', MovieController::class);
});

// Require multiple roles (OR logic)
Route::middleware(['auth', 'role:admin,moderator'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});

// Require specific permission
Route::middleware(['auth', 'permission:movies.create'])->group(function () {
    Route::post('/admin/movies', [MovieController::class, 'store']);
});

// Combine multiple middleware
Route::middleware(['auth', 'permission:payments.view'])->group(function () {
    Route::get('/admin/payments', [PaymentController::class, 'index']);
});
```

### Apply Middleware to Controllers

```php
class MovieController extends Controller
{
    public function __construct()
    {
        // Apply to all methods
        $this->middleware('permission:movies.view-all');
        
        // Apply to specific methods
        $this->middleware('permission:movies.create')->only(['create', 'store']);
        $this->middleware('permission:movies.edit')->only(['edit', 'update']);
        $this->middleware('permission:movies.delete')->only(['destroy']);
    }
}
```

---

## Policy Usage

### Available Policies

| Model | Policy | Methods |
|-------|--------|---------|
| `Movie` | `MoviePolicy` | viewAny, view, create, update, delete, publish, manageVideos |
| `Genre` | `GenrePolicy` | viewAny, view, create, update, delete |
| `User` | `UserPolicy` | viewAny, view, create, update, delete, manageRoles, ban |
| `Payment` | `PaymentPolicy` | viewAny, view, refund, manageSubscriptions, viewReports |

### Use Policies in Controllers

```php
class MovieController extends Controller
{
    public function index()
    {
        // Check policy
        $this->authorize('viewAny', Movie::class);
        
        $movies = Movie::all();
        return view('movies.index', compact('movies'));
    }
    
    public function show(Movie $movie)
    {
        // Check policy on specific model
        $this->authorize('view', $movie);
        
        return view('movies.show', compact('movie'));
    }
    
    public function update(Request $request, Movie $movie)
    {
        $this->authorize('update', $movie);
        
        $movie->update($request->validated());
        return redirect()->back();
    }
    
    public function destroy(Movie $movie)
    {
        $this->authorize('delete', $movie);
        
        $movie->delete();
        return redirect()->route('movies.index');
    }
}
```

### Manual Policy Checks

```php
// Check if user can perform action
if (auth()->user()->can('update', $movie)) {
    // User can update this movie
}

// Check if user cannot perform action
if (auth()->user()->cannot('delete', $movie)) {
    abort(403);
}

// Using Gate facade
use Illuminate\Support\Facades\Gate;

if (Gate::allows('update', $movie)) {
    // User can update
}

if (Gate::denies('delete', $movie)) {
    // User cannot delete
}
```

---

## Helper Functions

Global helper functions are available throughout the application:

```php
// Get current user
$user = current_user();

// Check role
if (user_has_role('admin')) {
    // User is admin
}

// Check permission
if (user_has_permission('movies.create')) {
    // User can create movies
}

// Check if admin
if (user_is_admin()) {
    // User is admin
}

// Check ability (policy)
if (user_can('update', $movie)) {
    // User can update movie
}

// Negative check
if (user_cannot('delete', $movie)) {
    // User cannot delete movie
}

// Abort if cannot (throws 403)
abort_unless_can('update', $movie);
abort_unless_has_role('admin');
abort_unless_has_permission('movies.create');
```

---

## Blade Directives

### Laravel's Built-in Authorization Directives

```blade
{{-- Check ability (policy) --}}
@can('update', $movie)
    <a href="{{ route('movies.edit', $movie) }}">Edit Movie</a>
@endcan

@cannot('delete', $movie)
    <p>You cannot delete this movie</p>
@endcannot

{{-- Check class-level ability --}}
@can('create', App\Models\Movie::class)
    <a href="{{ route('movies.create') }}">Create Movie</a>
@endcan

{{-- Multiple conditions --}}
@canany(['update', 'delete'], $movie)
    <div class="admin-actions">
        @can('update', $movie)
            <button>Edit</button>
        @endcan
        
        @can('delete', $movie)
            <button>Delete</button>
        @endcan
    </div>
@endcanany
```

### Using Helper Functions in Blade

```blade
{{-- Check role --}}
@if(user_has_role('admin'))
    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
@endif

{{-- Check permission --}}
@if(user_has_permission('movies.create'))
    <a href="{{ route('movies.create') }}">New Movie</a>
@endif

{{-- Check if admin --}}
@if(user_is_admin())
    <div class="admin-toolbar">...</div>
@endif

{{-- Authenticated user check --}}
@auth
    @if(user_can('update', $movie))
        <button>Edit</button>
    @endif
@endauth
```

---

## Database Seeding

### Run All Seeders

```bash
# Run migrations and seeders
php artisan migrate:fresh --seed

# Or run seeders only
php artisan db:seed
```

### Run Specific Seeders

```bash
# Seed roles
php artisan db:seed --class=RoleSeeder

# Seed permissions
php artisan db:seed --class=PermissionSeeder

# Seed admin users
php artisan db:seed --class=AdminUserSeeder
```

### Seeder Output

The seeders provide helpful console output:

```
🌱 Starting Kun Movie Platform Database Seeding...

📋 Seeding RBAC System...
✓ Roles seeded successfully!
  - Admin: Full system access
  - Moderator: Content moderation
  - Content Manager: Movie management
  - Support: Customer support
  - User: Standard user

Creating permissions...
  → movies permissions
  → genres permissions
  → users permissions
  → payments permissions
  → roles permissions
  → analytics permissions
  → settings permissions
✓ Permissions created successfully!

Assigning permissions to roles...
  ✓ Admin: All permissions assigned
  ✓ Moderator: Content management permissions assigned
  ✓ Content Manager: Movie & genre permissions assigned
  ✓ Support: Customer support permissions assigned
  ✓ User: Basic viewing permissions assigned

🎉 RBAC system seeded successfully!

Creating admin users...
  ✓ Super Admin created
    Email: admin@kun.com
    Password: password
  ✓ Moderator created
    Email: moderator@kun.com
    Password: password
  ✓ Content Manager created
    Email: content@kun.com
    Password: password
  ✓ Test User created
    Email: user@kun.com
    Password: password

✅ Database seeding completed successfully!

+------------------+-----------------------+----------+
| Role             | Email                 | Password |
+------------------+-----------------------+----------+
| Admin            | admin@kun.com         | password |
| Moderator        | moderator@kun.com     | password |
| Content Manager  | content@kun.com       | password |
| User             | user@kun.com          | password |
+------------------+-----------------------+----------+

⚠️  Don't forget to change default passwords in production!
```

---

## Testing Credentials

After seeding, use these credentials to test different permission levels:

| Role | Email | Password | Access |
|------|-------|----------|--------|
| **Admin** | admin@kun.com | password | Full access to everything |
| **Moderator** | moderator@kun.com | password | Content & user management |
| **Content Manager** | content@kun.com | password | Movie & genre management only |
| **User** | user@kun.com | password | Basic viewing only |

**⚠️ IMPORTANT:** Change these passwords in production environments!

---

## Best Practices

### 1. Use Policies for Model Authorization

✅ **Good:**
```php
$this->authorize('update', $movie);
```

❌ **Avoid:**
```php
if (!$user->hasPermission('movies.edit')) {
    abort(403);
}
```

### 2. Use Middleware for Route Protection

✅ **Good:**
```php
Route::middleware(['auth', 'permission:movies.create'])->group(function () {
    Route::post('/movies', [MovieController::class, 'store']);
});
```

❌ **Avoid:**
```php
public function store(Request $request)
{
    if (!auth()->user()->hasPermission('movies.create')) {
        abort(403);
    }
    // ...
}
```

### 3. Leverage Super Admin Gate

The system automatically grants all permissions to admin users:

```php
// In AppServiceProvider
Gate::before(function ($user, $ability) {
    if ($user->isAdmin()) {
        return true; // Admins bypass all checks
    }
});
```

### 4. Check Permissions, Not Roles

✅ **Good:**
```php
if ($user->hasPermission('movies.edit')) {
    // Edit movie
}
```

❌ **Avoid:**
```php
if ($user->hasRole(['admin', 'moderator', 'content-manager'])) {
    // Edit movie - brittle, hard to maintain
}
```

### 5. Use Blade Directives in Views

✅ **Good:**
```blade
@can('update', $movie)
    <button>Edit</button>
@endcan
```

❌ **Avoid:**
```blade
@if(auth()->user() && auth()->user()->hasPermission('movies.edit'))
    <button>Edit</button>
@endif
```

### 6. Document Custom Permissions

When adding new permissions, update:
- `PermissionSeeder.php` - Add the permission
- Role assignments in `PermissionSeeder.php`
- This documentation file

### 7. Test Authorization Logic

```php
// Feature test example
public function test_moderator_can_edit_movies()
{
    $moderator = User::factory()->create();
    $moderator->assignRole('moderator');
    
    $movie = Movie::factory()->create();
    
    $this->actingAs($moderator)
        ->get(route('movies.edit', $movie))
        ->assertOk();
}

public function test_regular_user_cannot_edit_movies()
{
    $user = User::factory()->create();
    $user->assignRole('user');
    
    $movie = Movie::factory()->create();
    
    $this->actingAs($user)
        ->get(route('movies.edit', $movie))
        ->assertForbidden();
}
```

---

## API Integration

### JSON Responses

All middleware and authorization failures return appropriate JSON responses for API requests:

```json
{
    "message": "Access denied. You do not have the required permission.",
    "error": "permission_denied",
    "required_permission": "movies.create"
}
```

### API Route Example

```php
// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('permission:movies.view-all')->group(function () {
        Route::get('/movies', [Api\MovieController::class, 'index']);
    });
    
    Route::middleware('permission:movies.create')->group(function () {
        Route::post('/movies', [Api\MovieController::class, 'store']);
    });
});
```

---

## Troubleshooting

### Permission Check Always Fails

1. Check if user has role assigned:
```php
dd($user->roles);
```

2. Check if role has permission:
```php
$role = $user->roles->first();
dd($role->permissions);
```

3. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
```

### Policy Not Working

1. Verify policy is registered in `AppServiceProvider.php`
2. Check model namespace matches
3. Use `php artisan policy:list` to view registered policies

### Middleware Not Applied

1. Check middleware alias in `bootstrap/app.php`
2. Verify route uses `auth` middleware first
3. Check middleware order in route definition

---

## Additional Resources

- [Laravel Authorization Documentation](https://laravel.com/docs/authorization)
- [Laravel Policies](https://laravel.com/docs/authorization#creating-policies)
- [Laravel Gates](https://laravel.com/docs/authorization#gates)
- [Laravel Middleware](https://laravel.com/docs/middleware)

---

**Built for Kun Movie Platform** 🎬  
Last Updated: 2026-08-12
