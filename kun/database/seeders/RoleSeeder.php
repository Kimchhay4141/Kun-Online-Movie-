<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full system access with all permissions. Can manage users, movies, payments, and system settings.',
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Can manage movies, genres, and moderate content. Limited access to user management.',
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content-manager',
                'description' => 'Can create, edit, and publish movies and genres. No access to user or payment management.',
            ],
            [
                'name' => 'Support',
                'slug' => 'support',
                'description' => 'Can view user information and assist with customer support. Read-only access to most resources.',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Standard user with access to watch movies, manage favorites, and personal profile.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        $this->command->info('✓ Roles seeded successfully!');
        $this->command->info('  - Admin: Full system access');
        $this->command->info('  - Moderator: Content moderation');
        $this->command->info('  - Content Manager: Movie management');
        $this->command->info('  - Support: Customer support');
        $this->command->info('  - User: Standard user');
    }
}
