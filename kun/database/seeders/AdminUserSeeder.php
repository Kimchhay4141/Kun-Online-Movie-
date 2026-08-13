<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating admin users...');

        // ==========================================
        // Super Admin
        // ==========================================
        $admin = User::updateOrCreate(
            ['email' => 'admin@kun.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_plan' => 'premium',
                'subscription_start' => now(),
                'subscription_end' => now()->addYear(),
            ]
        );

        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole && !$admin->hasRole('admin')) {
            $admin->roles()->attach($adminRole->id);
        }

        $this->command->info('  ✓ Super Admin created');
        $this->command->info('    Email: admin@kun.com');
        $this->command->info('    Password: password');

        // ==========================================
        // Moderator User
        // ==========================================
        $moderator = User::updateOrCreate(
            ['email' => 'moderator@kun.com'],
            [
                'name' => 'Moderator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $moderatorRole = Role::where('slug', 'moderator')->first();
        if ($moderatorRole && !$moderator->hasRole('moderator')) {
            $moderator->roles()->attach($moderatorRole->id);
        }

        $this->command->info('  ✓ Moderator created');
        $this->command->info('    Email: moderator@kun.com');
        $this->command->info('    Password: password');

        // ==========================================
        // Content Manager User
        // ==========================================
        $contentManager = User::updateOrCreate(
            ['email' => 'content@kun.com'],
            [
                'name' => 'Content Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $contentRole = Role::where('slug', 'content-manager')->first();
        if ($contentRole && !$contentManager->hasRole('content-manager')) {
            $contentManager->roles()->attach($contentRole->id);
        }

        $this->command->info('  ✓ Content Manager created');
        $this->command->info('    Email: content@kun.com');
        $this->command->info('    Password: password');

        // ==========================================
        // Test User
        // ==========================================
        $testUser = User::updateOrCreate(
            ['email' => 'user@kun.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_plan' => 'standard',
                'subscription_start' => now(),
                'subscription_end' => now()->addMonth(),
            ]
        );

        $userRole = Role::where('slug', 'user')->first();
        if ($userRole && !$testUser->hasRole('user')) {
            $testUser->roles()->attach($userRole->id);
        }

        $this->command->info('  ✓ Test User created');
        $this->command->info('    Email: user@kun.com');
        $this->command->info('    Password: password');

        $this->command->newLine();
        $this->command->info('🎉 Admin users created successfully!');
        $this->command->warn('⚠️  Remember to change these passwords in production!');
        $this->command->newLine();
    }
}
