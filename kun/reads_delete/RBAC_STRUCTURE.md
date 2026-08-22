# 🏗️ RBAC System Structure

## Database Schema

```
┌─────────────────────────────────────────────────────────────────┐
│                        RBAC ARCHITECTURE                        │
└─────────────────────────────────────────────────────────────────┘

┌──────────────┐         ┌──────────────┐         ┌──────────────┐
│    USERS     │         │    ROLES     │         │ PERMISSIONS  │
├──────────────┤         ├──────────────┤         ├──────────────┤
│ id           │         │ id           │         │ id           │
│ name         │◄───────►│ name         │◄───────►│ name         │
│ email        │  M:M    │ slug         │  M:M    │ slug         │
│ password     │         │ description  │         │ description  │
│ ...          │         │ created_at   │         │ group        │
└──────────────┘         │ updated_at   │         │ created_at   │
                         └──────────────┘         │ updated_at   │
                                                  └──────────────┘
      ↕                         ↕                         ↕
┌──────────────┐         ┌──────────────┐
│  ROLE_USER   │         │ PERMISSION_  │
│              │         │    ROLE      │
├──────────────┤         ├──────────────┤
│ user_id      │         │ permission_id│
│ role_id      │         │ role_id      │
└──────────────┘         └──────────────┘
```

## Permission Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    HOW PERMISSIONS WORK                         │
└─────────────────────────────────────────────────────────────────┘

User Request
    │
    ▼
┌──────────────────┐
│  Middleware      │ ← Check role/permission required
│  - admin         │
│  - role:X        │
│  - permission:X  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  Controller      │
│                  │
│  $this->         │
│  authorize()     │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  Policy          │ ← Fine-grained authorization
│  - viewAny()     │
│  - view()        │
│  - create()      │
│  - update()      │
│  - delete()      │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  User Model      │
│                  │
│  hasRole()       │ ← Check user's roles
│  hasPermission() │ ← Check via role's permissions
└────────┬─────────┘
         │
         ▼
    ✅ Authorized / ❌ 403 Forbidden
```

## Component Interaction

```
┌─────────────────────────────────────────────────────────────────┐
│                     COMPONENT LAYERS                            │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  ROUTE LAYER                                                    │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ Route::middleware(['auth', 'permission:movies.create'])    │ │
│  └────────────────────────────────────────────────────────────┘ │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│  MIDDLEWARE LAYER                                               │
│  ┌────────────────────┐  ┌────────────────────┐                │
│  │ AdminMiddleware    │  │ RoleMiddleware     │                │
│  │ - Checks isAdmin() │  │ - Checks hasRole() │                │
│  └────────────────────┘  └────────────────────┘                │
│  ┌────────────────────────────────────────────┐                │
│  │ PermissionMiddleware                       │                │
│  │ - Checks hasPermission()                   │                │
│  └────────────────────────────────────────────┘                │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│  CONTROLLER LAYER                                               │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ public function update(Movie $movie)                       │ │
│  │ {                                                          │ │
│  │     $this->authorize('update', $movie);                   │ │
│  │     // ... update logic                                   │ │
│  │ }                                                          │ │
│  └────────────────────────────────────────────────────────────┘ │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│  POLICY LAYER                                                   │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ MoviePolicy                                                │ │
│  │ - update(User $user, Movie $movie): bool                  │ │
│  │   return $user->hasPermission('movies.edit');             │ │
│  └────────────────────────────────────────────────────────────┘ │
└──────────────────────────────┬──────────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│  MODEL LAYER                                                    │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ User Model                                                 │ │
│  │ - hasRole($role)                                          │ │
│  │ - hasPermission($permission)                              │ │
│  │ - isAdmin()                                               │ │
│  │                                                           │ │
│  │ Relationships:                                            │ │
│  │ - roles() → Role (many-to-many)                          │ │
│  │ - permissions() → via roles                              │ │
│  └────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## Role Permission Matrix

```
┌──────────────────────────────────────────────────────────────────┐
│                    ROLE PERMISSION MATRIX                        │
└──────────────────────────────────────────────────────────────────┘

Permission              │ Admin │ Moderator │ Content │ Support │ User
                        │       │           │ Manager │         │
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
movies.view             │   ✓   │     ✓     │    ✓    │    ✓    │  ✓
movies.view-all         │   ✓   │     ✓     │    ✓    │    ✓    │  ✗
movies.create           │   ✓   │     ✓     │    ✓    │    ✗    │  ✗
movies.edit             │   ✓   │     ✓     │    ✓    │    ✗    │  ✗
movies.delete           │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
movies.publish          │   ✓   │     ✓     │    ✓    │    ✗    │  ✗
movies.manage-videos    │   ✓   │     ✓     │    ✓    │    ✗    │  ✗
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
genres.view             │   ✓   │     ✓     │    ✓    │    ✗    │  ✓
genres.create           │   ✓   │     ✓     │    ✓    │    ✗    │  ✗
genres.edit             │   ✓   │     ✓     │    ✓    │    ✗    │  ✗
genres.delete           │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
users.view              │   ✓   │     ✓     │    ✗    │    ✓    │  ✗
users.create            │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
users.edit              │   ✓   │     ✓     │    ✗    │    ✗    │  ✗
users.delete            │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
users.manage-roles      │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
users.ban               │   ✓   │     ✓     │    ✗    │    ✗    │  ✗
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
payments.view           │   ✓   │     ✗     │    ✗    │    ✓    │  ✗
payments.refund         │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
payments.manage-subs    │   ✓   │     ✗     │    ✗    │    ✓    │  ✗
payments.view-reports   │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
analytics.view          │   ✓   │     ✓     │    ✓    │    ✓    │  ✗
analytics.export        │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
roles.view              │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
roles.create            │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
roles.edit              │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
roles.delete            │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
roles.manage-perms      │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
────────────────────────┼───────┼───────────┼─────────┼─────────┼─────
settings.view           │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
settings.edit           │   ✓   │     ✗     │    ✗    │    ✗    │  ✗
```

## Policy Authorization Logic

```
┌──────────────────────────────────────────────────────────────────┐
│                      POLICY EXAMPLES                             │
└──────────────────────────────────────────────────────────────────┘

MoviePolicy::update()
├── Check: $user->hasPermission('movies.edit')
└── Result: ✓ Can edit / ✗ Cannot edit

UserPolicy::update()
├── Check 1: Is it the same user? ($user->id === $model->id)
│   └── YES → ✓ Allow (self-management)
└── Check 2: Has 'users.edit' permission?
    └── YES → ✓ Allow

UserPolicy::delete()
├── Check 1: Is it the same user? ($user->id === $model->id)
│   └── YES → ✗ Deny (cannot delete self)
├── Check 2: Is target an admin? ($model->isAdmin())
│   └── YES and you're not admin → ✗ Deny (cannot delete admins)
└── Check 3: Has 'users.delete' permission?
    └── YES → ✓ Allow

PaymentPolicy::view()
├── Check 1: Is it user's own payment? ($user->id === $payment->user_id)
│   └── YES → ✓ Allow (view own)
└── Check 2: Has 'payments.view' permission?
    └── YES → ✓ Allow (admin/support can view all)
```

## Helper Function Hierarchy

```
┌──────────────────────────────────────────────────────────────────┐
│                    HELPER FUNCTIONS                              │
└──────────────────────────────────────────────────────────────────┘

current_user()
└── Returns authenticated user or null

user_has_role($role)
├── Calls: current_user()
└── Calls: $user->hasRole($role)

user_has_permission($permission)
├── Calls: current_user()
└── Calls: $user->hasPermission($permission)

user_is_admin()
├── Calls: current_user()
└── Calls: $user->isAdmin()

user_can($ability, $model)
├── Calls: current_user()
└── Calls: $user->can($ability, $model)
    ├── Checks Policy
    └── Checks Gate

abort_unless_can($ability, $model)
├── Calls: user_can($ability, $model)
└── If false → abort(403)

abort_unless_has_permission($permission)
├── Calls: user_has_permission($permission)
└── If false → abort(403)
```

## Usage Flow Example

```
┌──────────────────────────────────────────────────────────────────┐
│               EXAMPLE: EDITING A MOVIE                           │
└──────────────────────────────────────────────────────────────────┘

1. User clicks "Edit" button
   └── URL: /admin/movies/123/edit

2. Route Definition
   └── Route::middleware(['auth', 'permission:movies.edit'])

3. PermissionMiddleware::handle()
   ├── Check: Is user authenticated?
   │   └── NO → Redirect to login
   ├── Check: user->hasPermission('movies.edit')?
   │   ├── YES → Continue to controller
   │   └── NO → Abort 403

4. MovieController::edit($movie)
   ├── $this->authorize('update', $movie)
   └── Calls MoviePolicy::update()

5. MoviePolicy::update($user, $movie)
   └── return $user->hasPermission('movies.edit');

6. User->hasPermission('movies.edit')
   ├── Load user's roles
   ├── Load roles' permissions
   ├── Check if 'movies.edit' exists in permissions
   └── Return true/false

7. Result
   ├── ✓ Authorized → Show edit form
   └── ✗ Denied → 403 Forbidden
```

---

**Visual representation of Kun Movie Platform RBAC System** 🎬
