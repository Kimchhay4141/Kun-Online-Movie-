#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   TESTING ADMIN ACCESS FIX                                     ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$user = User::where('email', 'admin@movieplatform.com')->first();

if (!$user) {
    echo "❌ Admin user not found!\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n\n";

echo "Role Check Results:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check roles
$roles = $user->roles;
echo "Roles assigned:\n";
foreach ($roles as $role) {
    echo "  • {$role->name} (slug: {$role->slug})\n";
}
echo "\n";

// Test different methods
echo "Method Tests:\n";
echo "  hasRole('admin'):  " . ($user->hasRole('admin') ? '✅ YES' : '❌ NO') . "\n";
echo "  hasRole('Admin'):  " . ($user->hasRole('Admin') ? '✅ YES' : '❌ NO') . "\n";
echo "  isAdmin():         " . ($user->isAdmin() ? '✅ YES' : '❌ NO') . " ← USED IN MIDDLEWARE\n";
echo "\n";

if ($user->isAdmin()) {
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║   ✅ SUCCESS! User will have access to admin dashboard         ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
} else {
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║   ❌ FAILED! User will NOT have access                         ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
}

echo "\n";
