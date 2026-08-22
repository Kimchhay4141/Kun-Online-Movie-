# 🎯 Complete Admin Login & RBAC Dashboard Guide

## 📋 Step-by-Step Instructions

### Step 1: Verify Database Connection

Run this command to check your database connection:

```bash
php artisan tinker --execute="echo 'Database connected! Total users: ' . App\Models\User::count()"
```

### Step 2: Run Migrations (If Not Already Done)

```bash
php artisan migrate
```

### Step 3: Seed Roles & Permissions

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

### Step 4: Create Admin User

Run the create-admin.php script:

```bash
php create-admin.php
```

OR use tinker:

```bash
php artisan tinker
```

Then paste this code:

```php
$user = App\Models\User::firstOrCreate(
    ['email' => 'admin@movieplatform.com'],
    [
        'name' => 'Admin User',
        'password' => Hash::make('admin123'),
        'email_verified_at' => now(),
    ]
);

$adminRole = App\Models\Role::where('slug', 'admin')->first();

if ($adminRole) {
    $user->syncRoles([$adminRole->id]);
    echo "✅ Admin user created successfully!\n";
    echo "Email: " . $user->email . "\n";
    echo "Password: admin123\n";
    echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
} else {
    echo "❌ Admin role not found! Please run RolePermissionSeeder first.\n";
}

exit
```

### Step 5: Start Your Laravel Application

```bash
php artisan serve
```

The application will start at: **http://localhost:8000**

### Step 6: Login as Admin

1. **Open your browser** and go to: **http://localhost:8000/login**

2. **Enter credentials:**
   - **Email:** `admin@movieplatform.com`
   - **Password:** `admin123`

3. **Click "Sign In"**

4. **You will be redirected to:** **http://localhost:8000/admin/dashboard**

---

## 🎨 Admin Dashboard Features

### After Login, You'll See:

#### 📊 Statistics Cards (6 cards):
1. **Movies** - Total movie count with percentage increase
2. **Users** - Total registered users
3. **Roles** - Total roles in the system
4. **Permissions** - Total permissions available
5. **Subscriptions** - Active subscriptions count
6. **New Users** - Users registered in last 30 days

#### ⚡ Quick Actions (6 buttons):
1. **Add Movie** - Create new movie
2. **Manage Users** - View all users
3. **Manage Roles** - Create & edit roles ⭐
4. **Manage Permissions** - Set permissions ⭐
5. **View Payments** - Payment records
6. **Reports** - View analytics

#### 📈 Analytics Section:
- **Platform Analytics Chart** - Line chart showing user growth and movie views
- **Users by Role Chart** - Pie chart showing distribution of users across roles

#### 🕒 Recent Activity:
- Shows latest user registrations
- Movie additions
- Role updates
- Permission changes

#### ⭐ Top Movies:
- Most viewed/revenue-generating movies
- Thumbnail previews
- View and revenue stats

---

## 🔐 RBAC Management Pages

### 1️⃣ Roles Management (`/admin/roles`)

**Features:**
- ✅ View all roles in a table
- ✅ See permission count for each role
- ✅ See user count assigned to each role
- ✅ Edit button (yellow) to modify role
- ✅ Delete button (red) to remove role
- ✅ Create new roles

**Actions Available:**
- Click **"Create Role"** button to add new role
- Click **Edit (yellow icon)** to modify existing role
- Click **Delete (red icon)** to remove role (protected roles can't be deleted)

### 2️⃣ Create Role Page (`/admin/roles/create`)

**Features:**
- Role name input field
- Role description textarea
- **Permissions grouped by module:**
  - Dashboard (3 permissions)
  - Movies (6 permissions)
  - Genres (4 permissions)
  - Users (6 permissions)
  - Roles (4 permissions)
  - Permissions (4 permissions)
  - Payments (3 permissions)
  - Moderation (2 permissions)
  - Analytics (2 permissions)
  - Settings (1 permission)

**How to Use:**
1. Enter role name (e.g., "Editor")
2. Enter description (e.g., "Can edit content but not delete")
3. Check the permissions you want to assign
4. Click **"Create Role"** button

### 3️⃣ Edit Role Page (`/admin/roles/{role}/edit`)

**Features:**
- Same as create page but with existing data pre-filled
- Can update role name and description
- Can add/remove permissions
- Shows role information sidebar

### 4️⃣ Permissions Management (`/admin/permissions`)

**Features:**
- View all permissions in a table
- Filter by module (dropdown)
- See permission code, name, and module
- Edit and delete actions
- Create new permissions

**Columns:**
- **Code** - Permission slug (e.g., `movie.create`)
- **Name** - Human-readable name
- **Module** - Category (Movies, Users, etc.)
- **Roles** - Number of roles using this permission
- **Actions** - Edit/Delete buttons

### 5️⃣ Users Management (`/admin/users`)

**Features:**
- View all users
- Create new users with role assignment
- Edit user roles
- Suspend/Activate users
- Cannot delete own account

---

## 🎯 Testing Checklist

Use this checklist to verify everything works:

### Login & Authentication
- [ ] Can access login page at `/login`
- [ ] Can login with admin credentials
- [ ] Redirected to admin dashboard after login
- [ ] See welcome message with admin name
- [ ] Cannot access admin pages without login

### Admin Dashboard
- [ ] Dashboard loads successfully at `/admin/dashboard`
- [ ] All 6 stat cards display correctly
- [ ] Quick action buttons are clickable
- [ ] Charts render properly (platform analytics & role distribution)
- [ ] Recent activity shows latest data
- [ ] Top movies section displays

### Roles Management
- [ ] Roles page loads at `/admin/roles`
- [ ] See all roles in table format
- [ ] Permission counts are correct
- [ ] User counts are accurate
- [ ] Can click "Create Role" button
- [ ] Create role form loads
- [ ] Permissions grouped by module
- [ ] Can check/uncheck permissions
- [ ] Can save new role
- [ ] Can edit existing role
- [ ] Can see updated data after edit
- [ ] Cannot delete system roles (Admin)
- [ ] Get error message when trying to delete protected roles

### Permissions Management
- [ ] Permissions page loads at `/admin/permissions`
- [ ] All permissions listed
- [ ] Can filter by module
- [ ] Each permission shows correct info
- [ ] Can create new permission
- [ ] Can edit permission
- [ ] Cannot delete permissions in use

### Users Management
- [ ] Users page loads at `/admin/users`
- [ ] Can see all users
- [ ] Can create new user with role
- [ ] Can assign roles to users
- [ ] Can edit user information
- [ ] Cannot delete own account
- [ ] Cannot delete super-admin users

### Authorization
- [ ] Admin can access all pages
- [ ] Admin can perform all actions
- [ ] Edit/Delete buttons show only when authorized
- [ ] Get proper error messages for unauthorized actions

---

## 📸 What You Should See

### Login Page
- Modern dark theme
- Email and password fields
- "Remember me" checkbox
- "Sign In" button with arrow
- KUN logo at top

### Admin Dashboard
- Dark theme with gradients
- 6 colorful stat cards (purple, blue, orange, green, pink, teal)
- Quick action cards with icons
- Line chart for platform analytics
- Pie chart for users by role
- Activity feed on left
- Top movies list on right

### Roles Page
- Clean table layout
- Yellow edit buttons
- Red delete buttons
- Badge indicators for counts
- "Create Role" button (blue) at top right

### Create/Edit Role Page
- Form with name and description fields
- Permissions grouped in expandable sections
- Checkboxes for each permission
- Save and Cancel buttons
- Clean, modern design

---

## 🔍 Troubleshooting

### Issue: Cannot Login
**Solution:**
1. Verify admin user exists:
   ```bash
   php artisan tinker --execute="echo App\Models\User::where('email', 'admin@movieplatform.com')->exists() ? 'Exists' : 'Not found'"
   ```
2. If not found, run create-admin script again

### Issue: "Access Denied" After Login
**Solution:**
1. Check if user has admin role:
   ```bash
   php artisan tinker
   ```
   ```php
   $user = App\Models\User::where('email', 'admin@movieplatform.com')->first();
   echo $user->roles->pluck('name');
   exit
   ```
2. If no roles, assign admin role

### Issue: Roles/Permissions Not Found
**Solution:**
1. Run seeder:
   ```bash
   php artisan db:seed --class=RolePermissionSeeder
   ```

### Issue: Page Not Found (404)
**Solution:**
1. Clear route cache:
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```

### Issue: Permission Denied Errors
**Solution:**
1. Make sure you're logged in as admin
2. Clear cache:
   ```bash
   php artisan cache:clear
   ```

---

## 🎉 Success Indicators

You know everything is working when:

✅ Login redirects to admin dashboard (not homepage)
✅ Dashboard shows accurate statistics
✅ All navigation links work
✅ Can create, edit, and delete roles
✅ Can manage permissions
✅ Can create users with role assignment
✅ Authorization works (proper access control)
✅ No console errors in browser
✅ All styles load correctly (dark theme)

---

## 🔐 Default Admin Credentials

**Email:** `admin@movieplatform.com`
**Password:** `admin123`

⚠️ **Important:** Change these credentials after first login in production!

---

## 📚 Quick Reference

### Available Routes

#### Authentication
- `/login` - Login page
- `/register` - Register page
- `/logout` - Logout (POST)

#### Admin Dashboard
- `/admin/dashboard` - Main dashboard

#### Roles Management
- `/admin/roles` - List all roles
- `/admin/roles/create` - Create new role
- `/admin/roles/{id}/edit` - Edit role
- `/admin/roles/{id}` - Delete role (DELETE)

#### Permissions Management
- `/admin/permissions` - List all permissions
- `/admin/permissions/create` - Create permission
- `/admin/permissions/{id}/edit` - Edit permission
- `/admin/permissions/{id}` - Delete permission (DELETE)

#### Users Management
- `/admin/users` - List all users
- `/admin/users/create` - Create new user
- `/admin/users/{id}/edit` - Edit user
- `/admin/users/{id}` - Delete user (DELETE)

---

## 🎨 UI Features

### Color Scheme
- **Purple** (#8b5cf6) - Movies, Primary actions
- **Blue** (#3b82f6) - Users, Information
- **Orange** (#f59e0b) - Roles, Warnings, Edit actions
- **Green** (#10b981) - Permissions, Success
- **Pink** (#ec4899) - Subscriptions, Premium
- **Teal** (#14b8a6) - Analytics, New items
- **Red** (#ef4444) - Delete actions, Errors

### Button Styles
- **Primary (Blue)** - Main actions, Create
- **Warning (Yellow/Orange)** - Edit actions
- **Danger (Red)** - Delete actions
- **Success (Green)** - Confirm actions
- **Secondary (Gray)** - Cancel, Back

### Icons Used
- 🎬 Movies - `fa-film`
- 👥 Users - `fa-users`
- 🏷️ Roles - `fa-user-tag`, `fa-user-shield`
- 🔒 Permissions - `fa-shield-alt`, `fa-lock`
- 💳 Payments - `fa-credit-card`
- 📊 Analytics - `fa-chart-line`, `fa-chart-bar`
- ⚡ Actions - `fa-bolt`
- ⏰ Time - `fa-clock`

---

## 🚀 Next Steps

After successful login and testing:

1. **Create More Roles**
   - Editor
   - Content Manager
   - Moderator
   - Viewer

2. **Create Test Users**
   - Assign different roles
   - Test permissions
   - Verify access control

3. **Customize Permissions**
   - Add new permissions for your features
   - Organize by modules
   - Assign to appropriate roles

4. **Test Authorization**
   - Login as different users
   - Try accessing restricted pages
   - Verify proper error messages

5. **Customize Dashboard**
   - Update statistics
   - Add your own widgets
   - Customize colors/theme

---

## 📞 Need Help?

If you encounter any issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for JavaScript errors
3. Verify database connection
4. Clear all caches: `php artisan optimize:clear`
5. Restart the development server

---

**Created:** August 15, 2026
**Status:** ✅ Ready to Use
**Version:** 1.0

---

## 🎯 Remember

> **Admin Dashboard = http://localhost:8000/admin/dashboard**
>
> **Login Email = admin@movieplatform.com**
>
> **Password = admin123**

**Happy Managing! 🎉**
