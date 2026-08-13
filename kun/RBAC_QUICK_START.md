# 🚀 RBAC Quick Start Guide

## Initial Setup

### 1. Run Migrations and Seeders

```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Or if already migrated, just seed
php artisan db:seed
```

### 2. Autoload Helper Functions

```bash
composer dump-autoload
```

---

## Test Accounts

After seeding, you can login with:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@kun.com | password |
| Moderator | moderator@kun.com | password |
| Content Manager | content@kun.com | password |
| User | user@kun.com | password |

---

## Quick Usage Examples

### In Controllers

```php
// Check permission before action
public function store(Request $request)
{
    $this->authorize('create', Movie::class);
    // or
    abort_unless_has_permission('movies.create');
    
    // Your logic here
}

// Check permission on specific model
public function update(Request $request, Movie $movie)
{
    $this->authorize('update', $movie);
    
    // Your logic here
}
```

### In Routes (web.php)

```php
// Protect admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
});

// Protect with specific permission
Route::middleware(['auth', 'permission:movies.create'])->group(function () {
    Route::get('/movies/create', [MovieController::class, 'create']);
    Route::post('/movies', [MovieController::class, 'store']);
});

// Protect with role
Route::middleware(['auth', 'role:moderator,content-manager'])->group(function () {
    Route::resource('/admin/movies', MovieController::class);
});
```

### In Blade Views

```blade
{{-- Show button only if user can update --}}
@can('update', $movie)
    <a href="{{ route('movies.edit', $movie) }}" class="btn">Edit</a>
@endcan

{{-- Check role --}}
@if(user_has_role('admin'))
    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
@endif

{{-- Check permission --}}
@if(user_has_permission('movies.create'))
    <a href="{{ route('movies.create') }}">New Movie</a>
@endif
```

### In PHP (anywhere)

```php
// Check if current user has role
if (user_has_role('admin')) {
    // Do admin stuff
}

// Check if current user has permission
if (user_has_permission('movies.edit')) {
    // Allow editing
}

// Check if user can perform action on model
if (user_can('update', $movie)) {
    // Show edit button
}

// Abort if user doesn't have permission (throws 403)
abort_unless_has_permission('movies.delete');
```

---

## Common Permission Slugs

### Movies
- `movies.view` - View published movies
- `movies.view-all` - View all movies (including drafts)
- `movies.create` - Create movies
- `movies.edit` - Edit movies
- `movies.delete` - Delete movies
- `movies.publish` - Publish/unpublish movies
- `movies.manage-videos` - Manage video files

### Genres
- `genres.view` - View genres
- `genres.create` - Create genres
- `genres.edit` - Edit genres
- `genres.delete` - Delete genres

### Users
- `users.view` - View users
- `users.create` - Create users
- `users.edit` - Edit users
- `users.delete` - Delete users
- `users.manage-roles` - Manage user roles
- `users.ban` - Ban users

### Payments
- `payments.view` - View payments
- `payments.refund` - Process refunds
- `payments.manage-subscriptions` - Manage subscriptions
- `payments.view-reports` - View financial reports

---

## Role Hierarchy

```
Admin (Full Access)
├── All Permissions
└── Bypasses all gate checks

Moderator
├── Movies (view-all, create, edit, publish, manage-videos)
├── Genres (view, create, edit)
├── Users (view, edit, ban)
└── Analytics (view)

Content Manager
├── Movies (view-all, create, edit, publish, manage-videos)
├── Genres (view, create, edit)
└── Analytics (view)

Support
├── Users (view)
├── Movies (view-all)
├── Payments (view, manage-subscriptions)
└── Analytics (view)

User
├── Movies (view)
└── Genres (view)
```

---

## Next Steps

1. ✅ RBAC system is ready to use
2. 🔨 Create your admin CRUD controllers (Movies, Genres, Users, Payments)
3. 🛡️ Apply middleware to protect routes
4. 📝 Use policies in controllers with `$this->authorize()`
5. 🎨 Use `@can` directives in Blade templates

---

## Full Documentation

For complete documentation, see: **[RBAC_DOCUMENTATION.md](RBAC_DOCUMENTATION.md)**

---

**Happy Coding! 🎬**
