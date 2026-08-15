#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "Checking admin user...\n\n";

$user = User::where('email', 'admin@movieplatform.com')->first();

if (!$user) {
    echo "❌ Admin user not found!\n";
    exit(1);
}

echo "✅ User found: {$user->name}\n";
echo "   Email: {$user->email}\n\n";

echo "Roles assigned to user:\n";
$roles = $user->roles;

if ($roles->isEmpty()) {
    echo "❌ NO ROLES ASSIGNED!\n\n";
    
    // Check if admin role exists
    $adminRole = Role::where('slug', 'admin')->first();
    if ($adminRole) {
        echo "Admin role exists in database. Assigning to user...\n";
        $user->roles()->attach($adminRole->id);
        echo "✅ Admin role assigned!\n";
    } else {
        echo "❌ Admin role not found in database!\n";
    }
} else {
    foreach ($roles as $role) {
        echo "   • Name: {$role->name}\n";
        echo "     Slug: {$role->slug}\n";
        echo "     ID: {$role->id}\n\n";
    }
}

// Check hasRole method
echo "Testing hasRole method:\n";
echo "   hasRole('admin'): " . ($user->hasRole('admin') ? 'YES ✅' : 'NO ❌') . "\n";
echo "   hasRole('Admin'): " . ($user->hasRole('Admin') ? 'YES ✅' : 'NO ❌') . "\n";

echo "\n";
