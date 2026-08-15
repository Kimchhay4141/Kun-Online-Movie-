<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@movieplatform.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
            ]
        );

        $adminRole = Role::where('slug', 'admin')->first();
        
        if ($adminRole && !$user->hasRole('admin')) {
            $user->assignRole($adminRole);
            $this->command->info('✅ Admin user created successfully!');
        } else {
            $this->command->info('✅ Admin user already exists!');
        }

        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('==================');
        $this->command->info('Email: admin@movieplatform.com');
        $this->command->info('Password: admin123');
        $this->command->info('');
        $this->command->info('Access admin at: http://localhost:8000/admin/dashboard');
    }
}
