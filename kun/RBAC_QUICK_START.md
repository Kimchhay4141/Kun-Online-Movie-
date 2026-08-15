# 🚀 RBAC Quick Start Guide

## Get Started in 5 Minutes!

---

## ✅ Step 1: Seed the Database

Open your terminal in the project directory and run:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

**Expected Output:**
```
✅ Permissions created successfully!
✅ Role 'Admin' created with 35 permissions
✅ Role 'Content Manager' created with 12 permissions
✅ Role 'Moderator' created with 9 permissions
✅ Role 'User' created with 1 permissions
✅ Role 'Premium User' created with 1 permissions
🎉 All roles and permissions created successfully!
```

---

## ✅ Step 2: Create Your First Admin User

Open Laravel Tinker:

```bash
php artisan tinker
```

Then run this code:

```php
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@movieplatform.com',
    'password' => Hash::make('admin123'),
]);

$adminRole = App\Models\Role::findBySlug('admin');
$user->assignRole($adminRole);

echo "✅ Admin user created successfully!";
exit
```

---

## ✅ Step 3: Login and Test

### 1. Start Your Server
```bash
php artisan serve
```

### 2. Login
- Go to: `http://localhost:8000/login`
- Email: `admin@movieplatform.com`
- Password: `admin123`

### 3. Access RBAC Pages

Visit these URLs to see your RBAC system:

#### **Roles Management**
`http://localhost:8000/admin/roles`
- View all roles
- See permission counts
- Edit/Delete roles

#### **Permissions Management**
`http://localhost:8000/admin/permissions`
- View all permissions
- Filter by module
- Edit/Delete permissions

#### **Users Management**
`http://localhost:8000/admin/users`
- View all users
- Create new users with roles
- Edit user roles

---

## 🎯 What You Can Do Now

### ✅ Create New Users

1. Go to `/admin/users/create`
2. Fill in the form:
   - Username
   - Email
   - Phone (optional)
   - Select Role (Admin, Content Manager, Moderator, User, or Premium User)
   - Password
3. Click "Save"

### ✅ Create New Roles

1. Go to `/admin/roles/create`
2. Enter role name and description
3. Select permissions from the grouped list
4. Click "Save Role"

### ✅ Manage Permissions

1. Go to `/admin/permissions`
2. View all 35 permissions
3. Filter by module (Dashboard, Movies, Genres, etc.)
4. Create new permissions as needed

---

## 📊 System Overview

### Roles Created:

| Role | Permissions | Description |
|------|-------------|-------------|
| **Admin** | 35 | Full system access |
| **Content Manager** | 12 | Manage movies & genres |
| **Moderator** | 9 | Moderate content & users |
| **User** | 1 | Basic user access |
| **Premium User** | 1 | Premium subscriber access |

### Permission Modules:

1. **Dashboard** (3 permissions)
2. **Movies** (6 permissions)
3. **Genres** (4 permissions)
4. **Users** (6 permissions)
5. **Roles** (4 permissions)
6. **Permissions** (4 permissions)
7. **Payments** (3 permissions)
8. **Moderation** (2 permissions)
9. **Analytics** (2 permissions)
10. **Settings** (1 permission)

---

## 🧪 Quick Test

### Test Admin Access:

```bash
# Login as admin
# Try to access:
- /admin/roles ✅ Should work
- /admin/permissions ✅ Should work
- /admin/users ✅ Should work
```

### Test User Model Methods:

```bash
php artisan tinker
```

```php
$user = User::first();

// Check role
$user->hasRole('admin'); // true

// Check permission
$user->hasPermission('movie.create'); // true

// Get all permissions
$user->permissions(); // Returns array of permission slugs

exit
```

---

## 🎨 UI Features

Your RBAC system includes:

✅ **Modern, Clean Design**
- Bootstrap 5 styling
- Responsive layout
- Color-coded buttons

✅ **Roles Page** (`/admin/roles`)
- Table with role names
- Permission count badges
- User count badges
- Yellow edit button
- Red delete button

✅ **Permissions Page** (`/admin/permissions`)
- Permission code display
- Module badges
- Filter by module
- Search functionality

✅ **Create User Page** (`/admin/users/create`)
- Username input
- Email input
- Phone input
- Active checkbox
- Role dropdown selector
- Password fields
- Save & Cancel buttons

---

## 🔥 Quick Commands Reference

```bash
# Seed roles & permissions
php artisan db:seed --class=RolePermissionSeeder

# View routes
php artisan route:list --name=admin.roles
php artisan route:list --name=admin.permissions
php artisan route:list --name=admin.users

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check migrations
php artisan migrate:status

# Open Tinker
php artisan tinker
```

---

## 💡 Pro Tips

1. **Always clear cache** after making RBAC changes:
   ```bash
   php artisan cache:clear
   ```

2. **Use policies in controllers**:
   ```php
   $this->authorize('create', Movie::class);
   ```

3. **Use Blade directives in views**:
   ```blade
   @can('create', App\Models\Movie::class)
       <button>Create Movie</button>
   @endcan
   ```

4. **Check permissions in code**:
   ```php
   if (auth()->user()->hasPermission('movie.create')) {
       // User can create movies
   }
   ```

---

## 🆘 Troubleshooting

### Problem: "Class RolePermissionSeeder not found"
**Solution:**
```bash
composer dump-autoload
php artisan db:seed --class=RolePermissionSeeder
```

### Problem: "Permission denied" errors
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
```

### Problem: Roles not showing
**Solution:**
```bash
# Re-run the seeder
php artisan db:seed --class=RolePermissionSeeder
```

### Problem: Cannot access admin pages
**Solution:**
- Make sure you're logged in as admin
- Check that admin middleware is applied
- Verify user has admin role:
  ```php
  php artisan tinker
  User::find(1)->roles; // Should show admin role
  ```

---

## 📚 Need More Help?

- **Full Documentation**: `RBAC_DOCUMENTATION.md`
- **Implementation Summary**: `RBAC_IMPLEMENTATION_SUMMARY.md`
- **Laravel Docs**: https://laravel.com/docs/authorization

---

## ✨ You're All Set!

Your RBAC system is ready to use. Enjoy managing your online movie platform with fine-grained access control!

**Happy Coding! 🎬🍿**
