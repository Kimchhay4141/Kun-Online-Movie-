#!/usr/bin/env php
<?php

/**
 * Quick Admin User Creator for KUN Movie
 * Run this file with: php create-admin.php
 */

define('LARAVEL_START', microtime(true));

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "\n";
echo "╔═══════════════════════════════════════════════════╗\n";
echo "║     KUN MOVIE - ADMIN ACCOUNT CREATOR            ║\n";
echo "╚═══════════════════════════════════════════════════╝\n";
echo "\n";

// Get input
echo "Enter admin name (default: Admin KUN): ";
$name = trim(fgets(STDIN));
$name = empty($name) ? 'Admin KUN' : $name;

echo "Enter admin email (default: admin@kun.com): ";
$email = trim(fgets(STDIN));
$email = empty($email) ? 'admin@kun.com' : $email;

echo "Enter admin password (default: admin123): ";
$password = trim(fgets(STDIN));
$password = empty($password) ? 'admin123' : $password;

echo "\n";
echo "Creating admin user...\n";

try {
    // Check if user already exists
    $existingUser = User::where('email', $email)->first();
    if ($existingUser) {
        echo "❌ User with email '{$email}' already exists!\n";
        echo "\nWould you like to make this user an admin? (y/n): ";
        $makeAdmin = trim(fgets(STDIN));
        
        if (strtolower($makeAdmin) === 'y') {
            $user = $existingUser;
        } else {
            echo "Aborted.\n\n";
            exit(1);
        }
    } else {
        // Create new user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'subscription_status' => 'active',
            'subscription_plan' => 'premium'
        ]);
        echo "✓ User created successfully!\n";
    }

    // Get or create admin role
    $adminRole = Role::where('name', 'admin')->first();
    
    if (!$adminRole) {
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access'
        ]);
        echo "✓ Admin role created!\n";
    }

    // Attach admin role
    if (!$user->hasRole('admin')) {
        $user->roles()->attach($adminRole->id);
        echo "✓ Admin role assigned!\n";
    } else {
        echo "⚠ User already has admin role!\n";
    }

    echo "\n";
    echo "╔═══════════════════════════════════════════════════╗\n";
    echo "║           ✓ ADMIN CREATED SUCCESSFULLY           ║\n";
    echo "╚═══════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "Login Credentials:\n";
    echo "──────────────────\n";
    echo "Email:    {$email}\n";
    echo "Password: {$password}\n";
    echo "\n";
    echo "Access Admin Dashboard:\n";
    echo "─────────────────────\n";
    echo "URL: http://127.0.0.1:8000/admin/dashboard\n";
    echo "\n";
    echo "🎉 You can now login and manage your KUN Movie platform!\n";
    echo "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
