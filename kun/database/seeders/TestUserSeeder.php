<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->command->info('Creating regular test users...');

        // Get the 'user' role
        $userRole = Role::where('slug', 'user')->first();

        $testUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'active',
                'subscription_plan' => 'premium',
                'subscription_start' => now()->subMonths(2),
                'subscription_end' => now()->addMonths(10),
                'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=e50914&color=fff',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'active',
                'subscription_plan' => 'standard',
                'subscription_start' => now()->subMonth(),
                'subscription_end' => now()->addMonths(11),
                'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&background=46d369&color=fff',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'free',
                'subscription_plan' => 'free',
                'avatar' => 'https://ui-avatars.com/api/?name=Bob+Johnson&background=1e90ff&color=fff',
            ],
            [
                'name' => 'Alice Williams',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'active',
                'subscription_plan' => 'premium',
                'subscription_start' => now()->subMonths(6),
                'subscription_end' => now()->addMonths(6),
                'avatar' => 'https://ui-avatars.com/api/?name=Alice+Williams&background=ff69b4&color=fff',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'cancelled',
                'subscription_plan' => 'standard',
                'subscription_start' => now()->subMonths(3),
                'subscription_end' => now()->subMonth(),
                'avatar' => 'https://ui-avatars.com/api/?name=Charlie+Brown&background=ffa500&color=fff',
            ],
            [
                'name' => 'Diana Prince',
                'email' => 'diana@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'active',
                'subscription_plan' => 'premium',
                'subscription_start' => now()->subWeeks(2),
                'subscription_end' => now()->addMonths(11)->addWeeks(2),
                'avatar' => 'https://ui-avatars.com/api/?name=Diana+Prince&background=9c27b0&color=fff',
            ],
            [
                'name' => 'Ethan Hunt',
                'email' => 'ethan@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'free',
                'subscription_plan' => 'free',
                'avatar' => 'https://ui-avatars.com/api/?name=Ethan+Hunt&background=607d8b&color=fff',
            ],
            [
                'name' => 'Fiona Green',
                'email' => 'fiona@example.com',
                'password' => Hash::make('password'),
                'subscription_status' => 'active',
                'subscription_plan' => 'standard',
                'subscription_start' => now()->subDays(15),
                'subscription_end' => now()->addMonths(11)->addDays(15),
                'avatar' => 'https://ui-avatars.com/api/?name=Fiona+Green&background=4caf50&color=fff',
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::create($userData);
            
            // Assign 'user' role
            if ($userRole) {
                $user->roles()->attach($userRole->id);
            }

            $this->command->info("  ✓ Created: {$user->name} ({$user->email})");
        }

        $this->command->info('✅ Test users created successfully!');
    }
}
