#!/usr/bin/env php
<?php

/**
 * KUN Movie Platform - Admin Setup Verification Script
 * Run this to verify your admin setup is correct
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   KUN MOVIE PLATFORM - ADMIN SETUP VERIFICATION                ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Check database connection
echo "🔍 Checking database connection...\n";
try {
    \DB::connection()->getPdo();
    echo "   ✅ Database connected successfully!\n\n";
} catch (\Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Check if tables exist
echo "📋 Checking tables...\n";
$tables = ['users', 'roles', 'permissions', 'role_user', 'permission_role'];
$missingTables = [];

foreach ($tables as $table) {
    try {
        \DB::table($table)->limit(1)->get();
        echo "   ✅ Table '$table' exists\n";
    } catch (\Exception $e) {
        echo "   ❌ Table '$table' missing\n";
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "\n⚠️  Missing tables! Run: php artisan migrate\n\n";
    exit(1);
}

echo "\n";

// Check roles
echo "🏷️  Checking roles...\n";
$rolesCount = Role::count();
echo "   Total roles: $rolesCount\n";

if ($rolesCount === 0) {
    echo "   ⚠️  No roles found! Run: php artisan db:seed --class=RolePermissionSeeder\n\n";
    exit(1);
}

$roles = Role::all();
foreach ($roles as $role) {
    $permCount = $role->permissions()->count();
    echo "   • {$role->name} (slug: {$role->slug}) - {$permCount} permissions\n";
}

echo "\n";

// Check permissions
echo "🔒 Checking permissions...\n";
$permissionsCount = Permission::count();
echo "   Total permissions: $permissionsCount\n";

if ($permissionsCount === 0) {
    echo "   ⚠️  No permissions found! Run: php artisan db:seed --class=RolePermissionSeeder\n\n";
    exit(1);
}

$groups = Permission::select('group')->distinct()->pluck('group');
echo "   Groups: " . $groups->implode(', ') . "\n";

echo "\n";

// Check admin role
echo "👑 Checking admin role...\n";
$adminRole = Role::where('slug', 'admin')->orWhere('slug', 'Admin')->first();

if (!$adminRole) {
    echo "   ❌ Admin role not found!\n";
    echo "   ⚠️  Run: php artisan db:seed --class=RolePermissionSeeder\n\n";
    exit(1);
}

echo "   ✅ Admin role found: {$adminRole->name}\n";
echo "   Permissions: " . $adminRole->permissions()->count() . "\n";

echo "\n";

// Check admin user
echo "👤 Checking admin user...\n";
$adminUser = User::whereHas('roles', function($q) {
    $q->where('slug', 'admin')->orWhere('slug', 'Admin');
})->first();

if (!$adminUser) {
    echo "   ⚠️  No admin user found!\n";
    echo "   Creating admin user...\n\n";
    
    $user = User::firstOrCreate(
        ['email' => 'admin@movieplatform.com'],
        [
            'name' => 'Admin User',
            'password' => \Hash::make('admin123'),
            'email_verified_at' => now(),
        ]
    );
    
    $user->syncRoles([$adminRole->id]);
    
    echo "   ✅ Admin user created!\n";
    echo "   Email: {$user->email}\n";
    echo "   Password: admin123\n";
    echo "   Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
} else {
    echo "   ✅ Admin user exists!\n";
    echo "   Name: {$adminUser->name}\n";
    echo "   Email: {$adminUser->email}\n";
    echo "   Roles: " . $adminUser->roles->pluck('name')->implode(', ') . "\n";
    
    // Count permissions through roles
    $totalPermissions = 0;
    foreach ($adminUser->roles as $role) {
        $totalPermissions += $role->permissions()->count();
    }
    echo "   Permissions: {$totalPermissions}\n";
}

echo "\n";

// Check routes
echo "🛣️  Checking routes...\n";
$routes = [
    'login' => 'Login page',
    'admin.dashboard' => 'Admin dashboard',
    'admin.roles.index' => 'Roles management',
    'admin.permissions.index' => 'Permissions management',
    'admin.users.index' => 'Users management',
];

foreach ($routes as $routeName => $description) {
    try {
        $url = route($routeName, [], false);
        echo "   ✅ {$description}: {$url}\n";
    } catch (\Exception $e) {
        echo "   ❌ {$description} route not found!\n";
    }
}

echo "\n";

// Summary
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   SETUP VERIFICATION COMPLETE                                  ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Get total counts
$usersCount = User::count();
$rolesCount = Role::count();
$permissionsCount = Permission::count();

echo "📊 Statistics:\n";
echo "   • Users: $usersCount\n";
echo "   • Roles: $rolesCount\n";
echo "   • Permissions: $permissionsCount\n";

echo "\n";
echo "🎯 Next Steps:\n";
echo "   1. Start server: php artisan serve\n";
echo "   2. Open browser: http://localhost:8000/login\n";
echo "   3. Login with:\n";
echo "      Email: admin@movieplatform.com\n";
echo "      Password: admin123\n";
echo "   4. You'll be redirected to: http://localhost:8000/admin/dashboard\n";

echo "\n";
echo "✨ Your KUN Movie Platform admin is ready!\n";
echo "\n";
