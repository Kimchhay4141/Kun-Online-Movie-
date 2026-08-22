# KUN Login Flow - Complete Implementation Plan

## 📋 Overview

This document outlines the complete authentication and authorization flow for the KUN movie streaming platform, detailing how visitors, users, and admins interact with the system.

---

## 🔄 User Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                      VISITOR (Guest)                         │
│                  Lands on Public Homepage                    │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ├──► Browse Movies (Public)
                       ├──► View Movie Details (Public)
                       ├──► Search & Filter (Public)
                       ├──► Browse by Genre (Public)
                       │
                       │ Try to Like/Favorite?
                       └──► Redirect to LOGIN
                              │
        ┌─────────────────────┴─────────────────────┐
        │                                            │
    [LOGIN]                                    [REGISTER]
        │                                            │
        │ Submit Credentials                         │ Create Account
        │                                            │
        └────────────► Authentication ◄──────────────┘
                            │
                    Check User Role
                            │
            ┌───────────────┴───────────────┐
            │                               │
       [ADMIN ROLE]                    [USER ROLE]
            │                               │
            ▼                               ▼
    ┌───────────────┐           ┌────────────────────┐
    │     ADMIN     │           │    NORMAL USER     │
    │   DASHBOARD   │           │    USER HOME       │
    └───────────────┘           └────────────────────┘
            │                               │
            ├─► Manage Movies               ├─► Browse Movies
            ├─► Manage Users                ├─► Favorites
            ├─► Manage Genres               ├─► Watchlist (My List)
            ├─► View Statistics             ├─► Watch Movies
            ├─► Payment Management          ├─► Watch History
            └─► System Settings             ├─► Profile Settings
                                            └─► Subscription Management
```

---

## 🎭 User Types & Access Levels

### 1. **VISITOR (Unauthenticated Guest)**

#### Can Access:
- ✅ Public Homepage (`/`)
- ✅ Browse Movies (`/movies`)
- ✅ View Movie Details (`/movie/{id}`)
- ✅ Search Movies (`/search`)
- ✅ Browse by Genre (`/genre/{slug}`)
- ✅ View Genres Page (`/genres`)
- ✅ Static Pages (About, Contact, Terms, etc.)

#### Cannot Access:
- ❌ Like/Favorite Movies
- ❌ Add to Watchlist
- ❌ Watch Movies
- ❌ User Profile
- ❌ Watch History
- ❌ Admin Dashboard

#### Behavior:
When attempting to access protected features:
- System shows **Login/Register prompt**
- User is redirected to login page
- After successful login, user is redirected to original intended page

---

### 2. **NORMAL USER (Authenticated with "user" role)**

#### Can Access:
- ✅ All Visitor features (above)
- ✅ **Home Page** with personalized content
- ✅ **Favorites** - Like and manage favorite movies
- ✅ **Watchlist (My List)** - Add movies to watch later
- ✅ **Watch Movies** - Stream movie content
- ✅ **Watch History** - View recently watched movies
- ✅ **Continue Watching** - Resume partially watched movies
- ✅ **User Profile** - Edit profile, avatar, preferences
- ✅ **Subscription Management** - View/manage subscription
- ✅ **Payment History** - View past payments

#### Cannot Access:
- ❌ Admin Dashboard
- ❌ Manage Movies (Create/Edit/Delete)
- ❌ Manage Users
- ❌ Manage Genres
- ❌ System Statistics
- ❌ Payment Management (Admin view)

#### Routes Available:
```php
/                           // Home (personalized)
/movies                     // Browse movies
/movie/{id}                 // Movie details
/movie/{id}/watch           // Watch movie
/favorites                  // Favorite movies
/my-list                    // Watchlist
/history                    // Watch history
/continue-watching          // Resume watching
/profile                    // User profile
/subscription/plans         // Subscription plans
/payments/history           // Payment history
```

#### After Login Redirect:
```php
// Normal User → Home
return redirect()->route('home')
    ->with('success', 'Welcome back, ' . $user->name . '!');
```

---

### 3. **ADMIN (Authenticated with "admin" role)**

#### Can Access:
- ✅ All Normal User features (above)
- ✅ **Admin Dashboard** - System overview & statistics
- ✅ **Manage Movies** - Create, Edit, Delete, Publish movies
- ✅ **Manage Genres** - Create, Edit, Delete genres
- ✅ **Manage Users** - View, Edit, Suspend users
- ✅ **View All Payments** - System-wide payment records
- ✅ **System Statistics** - Views, Revenue, User metrics
- ✅ **Bulk Operations** - Mass update/delete operations
- ✅ **Export Data** - Generate reports

#### Routes Available:
```php
// All user routes PLUS:
/admin/dashboard            // Admin dashboard
/admin/movies               // Movies management
/admin/movies/{id}/edit     // Edit movie
/admin/genres               // Genres management
/admin/users                // Users management
/admin/payments             // Payments management
/admin/stats/refresh        // Statistics refresh
/admin/bulk-action          // Bulk operations
/admin/export               // Export data
```

#### After Login Redirect:
```php
// Admin → Admin Dashboard
return redirect()->route('admin.dashboard')
    ->with('success', 'Welcome back, Admin ' . $user->name . '!');
```

---

## 🔐 Authentication Flow

### Step 1: Login Request

**Route:** `POST /login`

**Controller:** `LoginController::login()`

```php
public function login(Request $request)
{
    // 1. Validate credentials
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    
    $remember = $request->boolean('remember');
    
    // 2. Attempt authentication
    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        $user = Auth::user();
        
        // 3. Check user role
        if ($user->isAdmin()) {
            // Admin → Dashboard
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, Admin ' . $user->name . '!');
        }
        
        // Normal User → Home
        return redirect()->route('home')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }
    
    // 4. Failed login
    throw ValidationException::withMessages([
        'email' => ['The provided credentials do not match our records.'],
    ]);
}
```

---

### Step 2: Registration Flow

**Route:** `POST /register`

**Controller:** `RegisterController::register()`

```php
public function register(Request $request)
{
    // 1. Validate input
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'confirmed', Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()],
        'terms' => ['accepted'],
    ]);
    
    // 2. Create user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'subscription_status' => 'free',
        'subscription_plan' => 'free',
    ]);
    
    // 3. Assign default "user" role
    $userRole = Role::where('name', 'user')->first();
    if ($userRole) {
        $user->roles()->attach($userRole->id);
    }
    
    // 4. Auto-login and redirect
    Auth::login($user);
    $request->session()->regenerate();
    
    return redirect()->route('home')
        ->with('success', 'Welcome to Kun, ' . $user->name . '!');
}
```

---

## 🛡️ Middleware Protection

### 1. **Authentication Middleware**

**File:** `app/Http/Middleware/Authenticate.php` (Laravel default)

**Usage:** Protects routes that require login

```php
Route::middleware(['auth'])->group(function () {
    // Protected routes here
});
```

**Behavior:**
- If not logged in → Redirect to `/login`
- If logged in → Allow access

---

### 2. **Admin Middleware**

**File:** `app/Http/Middleware/AdminMiddleware.php`

**Usage:** Protects admin-only routes

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes here
});
```

**Implementation:**
```php
public function handle(Request $request, Closure $next): Response
{
    // Check authentication
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Please login to access this page.');
    }
    
    // Check admin role
    if (!auth()->user()->hasRole('admin')) {
        abort(403, 'Unauthorized access. Admin privileges required.');
    }
    
    return $next($request);
}
```

---

### 3. **Role Middleware**

**File:** `app/Http/Middleware/RoleMiddleware.php`

**Usage:** Flexible role-based protection

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Routes requiring specific role
});
```

**Implementation:**
```php
public function handle(Request $request, Closure $next, string $role): Response
{
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Please login to access this page.');
    }
    
    if (!auth()->user()->hasRole($role)) {
        abort(403, "Unauthorized access. '{$role}' role required.");
    }
    
    return $next($request);
}
```

---

## 🎯 Role-Based Access Control (RBAC)

### User Model Helper Methods

**File:** `app/Models/User.php`

```php
// Check if user is admin
public function isAdmin(): bool
{
    return $this->hasRole('admin');
}

// Check if user has specific role
public function hasRole(string|array $role): bool
{
    if (is_array($role)) {
        return $this->roles->whereIn('name', $role)->isNotEmpty();
    }
    return $this->roles->contains('name', $role);
}

// Check if user has specific permission
public function hasPermission(string|array $permission): bool
{
    return in_array($permission, $this->permissions());
}
```

### Blade Template Usage

```blade
@auth
    <!-- Logged in users -->
    
    @if(auth()->user()->isAdmin())
        <!-- Admin only content -->
        <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
    @else
        <!-- Normal user content -->
        <a href="{{ route('profile.show') }}">My Profile</a>
    @endif
@else
    <!-- Guests -->
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>
@endauth
```

---

## 🚀 Implementation Checklist

### ✅ Already Implemented

- [x] User model with RBAC methods
- [x] Role and Permission models
- [x] LoginController with role-based redirect
- [x] RegisterController with default role assignment
- [x] AdminMiddleware for admin route protection
- [x] RoleMiddleware for flexible role protection
- [x] Route definitions (public, user, admin)
- [x] HomeController with public/auth content differentiation

### 📝 Implementation Tasks

#### Phase 1: Authentication Views
- [ ] Create login view (`resources/views/auth/login.blade.php`)
- [ ] Create registration view (`resources/views/auth/register.blade.php`)
- [ ] Create password reset views
- [ ] Add "Login to Like" modal/prompt for guests

#### Phase 2: User Dashboard
- [ ] Create user home view with personalized content
- [ ] Create favorites page
- [ ] Create watchlist page
- [ ] Create watch history page
- [ ] Create user profile page

#### Phase 3: Admin Dashboard
- [ ] Create admin dashboard view
- [ ] Create movie management interface
- [ ] Create user management interface
- [ ] Create genre management interface
- [ ] Create statistics/analytics page

#### Phase 4: Navigation & UI
- [ ] Update navbar with role-based menu items
- [ ] Add "Login Required" prompts on protected actions
- [ ] Add role badges (Admin, Premium, etc.)
- [ ] Implement smooth redirect flow after login

#### Phase 5: Testing
- [ ] Test guest access (can browse, cannot like)
- [ ] Test user login → correct home redirect
- [ ] Test admin login → dashboard redirect
- [ ] Test middleware protection (403 for unauthorized)
- [ ] Test role switching and permissions

---

## 🔄 Login Flow Examples

### Example 1: Guest Tries to Like Movie

```
1. Guest clicks "Like" button on movie
2. JavaScript detects no auth session
3. Show modal: "Please login to add favorites"
4. Redirect to /login with ?redirect=/movie/123
5. User logs in
6. System redirects to /movie/123
7. User can now click "Like" successfully
```

### Example 2: Normal User Login

```
1. User visits /login
2. Enters email: john@example.com, password: ******
3. System validates credentials ✓
4. System checks role: "user"
5. Redirect to /home (user homepage)
6. Show success message: "Welcome back, John!"
```

### Example 3: Admin Login

```
1. Admin visits /login
2. Enters email: admin@kun.com, password: ******
3. System validates credentials ✓
4. System checks role: "admin"
5. Redirect to /admin/dashboard
6. Show success message: "Welcome back, Admin Sarah!"
```

### Example 4: Unauthorized Access Attempt

```
1. Normal user tries to visit /admin/dashboard
2. AdminMiddleware checks role
3. User does NOT have "admin" role
4. Return 403 Forbidden error
5. Show: "Unauthorized access. Admin privileges required."
```

---

## 🎨 UI/UX Considerations

### 1. **Guest Experience**
- Clear indication of what requires login
- Smooth login prompts (modals, not aggressive redirects)
- "Continue as Guest" option where applicable
- Preview content before requiring registration

### 2. **User Experience**
- Personalized homepage after login
- Easy access to favorites and watchlist
- "Remember me" option on login
- Clear role indicators in UI

### 3. **Admin Experience**
- Quick access to admin dashboard
- Role badge in navigation
- Separate admin navigation menu
- Quick stats on dashboard

---

## 📊 Database Schema

### Users Table
```sql
- id
- name
- email (unique)
- password
- role_id → references roles(id)
- subscription_plan
- subscription_status
- created_at
- updated_at
```

### Roles Table
```sql
- id
- name (admin, user, moderator, etc.)
- description
- created_at
- updated_at
```

### Role_User Pivot Table
```sql
- id
- user_id → references users(id)
- role_id → references roles(id)
- created_at
- updated_at
```

---

## 🔒 Security Best Practices

1. **Password Security**
   - Minimum 8 characters
   - Mixed case required
   - Numbers required
   - Hashed using bcrypt

2. **Session Security**
   - Session regeneration after login
   - CSRF protection on all forms
   - Secure cookie flags in production

3. **Authorization**
   - Always check both authentication AND role
   - Use middleware consistently
   - Never trust client-side role checks

4. **Rate Limiting**
   - Limit login attempts
   - Throttle registration
   - Protect admin routes

---

## 📝 Next Steps

1. **Create Authentication Views**
   - Design login page UI
   - Design registration page UI
   - Add social login buttons (optional)

2. **Implement User Dashboard**
   - Personalized homepage
   - Favorites management
   - Watchlist functionality

3. **Build Admin Dashboard**
   - Statistics overview
   - Management interfaces
   - Bulk operations

4. **Add Guest Prompts**
   - "Login to Like" modals
   - "Sign up to Watch" prompts
   - Smooth redirect flows

5. **Testing & Refinement**
   - Test all user flows
   - Verify middleware protection
   - Check edge cases

---

## 📚 Resources

### Existing Files
- **Controllers:** `app/Http/Controllers/Auth/`
- **Middleware:** `app/Http/Middleware/`
- **Models:** `app/Models/User.php`
- **Routes:** `routes/web.php`

### Key Routes
- Login: `/login`
- Register: `/register`
- User Home: `/`
- Admin Dashboard: `/admin/dashboard`

---

## 💡 Tips

1. **Always check both `auth()` and role** in protected routes
2. **Use middleware groups** for consistent protection
3. **Provide clear feedback** on unauthorized access
4. **Test with different user types** regularly
5. **Keep role logic in the User model** for reusability

---

**Created:** 2026-08-13  
**Version:** 1.0  
**Status:** Ready for Implementation
