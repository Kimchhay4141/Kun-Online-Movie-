<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Kun Movie Platform Database Seeding...');
        $this->command->newLine();

        // Seed RBAC System
        $this->command->info('📋 Seeding RBAC System...');
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎬 Seeding Content...');
        
        $this->call([
            GenreSeeder::class,
            MovieSeeder::class,
            ComingSoonSeeder::class,
            MovieVideoSeeder::class,
            TestUserSeeder::class,
            FavoriteSeeder::class,
            WatchlistSeeder::class,
            MovieViewSeeder::class,
            PaymentSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@kun.com', 'password'],
                ['Moderator', 'moderator@kun.com', 'password'],
                ['Content Manager', 'content@kun.com', 'password'],
                ['User', 'user@kun.com', 'password'],
            ]
        );
        
        $this->command->newLine();
        $this->command->warn('⚠️  Don\'t forget to change default passwords in production!');
    }
}
