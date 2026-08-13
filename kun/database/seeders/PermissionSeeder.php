<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions grouped by resource
        $permissions = [
            // ==========================================
            // Movie Management Permissions
            // ==========================================
            'movies' => [
                [
                    'name' => 'View Movies',
                    'slug' => 'movies.view',
                    'description' => 'Can view published movies',
                ],
                [
                    'name' => 'View All Movies',
                    'slug' => 'movies.view-all',
                    'description' => 'Can view all movies including drafts',
                ],
                [
                    'name' => 'Create Movies',
                    'slug' => 'movies.create',
                    'description' => 'Can create new movies',
                ],
                [
                    'name' => 'Edit Movies',
                    'slug' => 'movies.edit',
                    'description' => 'Can edit existing movies',
                ],
                [
                    'name' => 'Delete Movies',
                    'slug' => 'movies.delete',
                    'description' => 'Can delete movies',
                ],
                [
                    'name' => 'Publish Movies',
                    'slug' => 'movies.publish',
                    'description' => 'Can publish/unpublish movies',
                ],
                [
                    'name' => 'Manage Movie Videos',
                    'slug' => 'movies.manage-videos',
                    'description' => 'Can upload and manage movie video files',
                ],
            ],

            // ==========================================
            // Genre Management Permissions
            // ==========================================
            'genres' => [
                [
                    'name' => 'View Genres',
                    'slug' => 'genres.view',
                    'description' => 'Can view all genres',
                ],
                [
                    'name' => 'Create Genres',
                    'slug' => 'genres.create',
                    'description' => 'Can create new genres',
                ],
                [
                    'name' => 'Edit Genres',
                    'slug' => 'genres.edit',
                    'description' => 'Can edit existing genres',
                ],
                [
                    'name' => 'Delete Genres',
                    'slug' => 'genres.delete',
                    'description' => 'Can delete genres',
                ],
            ],

            // ==========================================
            // User Management Permissions
            // ==========================================
            'users' => [
                [
                    'name' => 'View Users',
                    'slug' => 'users.view',
                    'description' => 'Can view user list and profiles',
                ],
                [
                    'name' => 'Create Users',
                    'slug' => 'users.create',
                    'description' => 'Can create new users',
                ],
                [
                    'name' => 'Edit Users',
                    'slug' => 'users.edit',
                    'description' => 'Can edit user information',
                ],
                [
                    'name' => 'Delete Users',
                    'slug' => 'users.delete',
                    'description' => 'Can delete users',
                ],
                [
                    'name' => 'Manage User Roles',
                    'slug' => 'users.manage-roles',
                    'description' => 'Can assign/remove user roles',
                ],
                [
                    'name' => 'Ban Users',
                    'slug' => 'users.ban',
                    'description' => 'Can ban/unban users',
                ],
            ],

            // ==========================================
            // Payment Management Permissions
            // ==========================================
            'payments' => [
                [
                    'name' => 'View Payments',
                    'slug' => 'payments.view',
                    'description' => 'Can view payment transactions',
                ],
                [
                    'name' => 'Process Refunds',
                    'slug' => 'payments.refund',
                    'description' => 'Can process payment refunds',
                ],
                [
                    'name' => 'Manage Subscriptions',
                    'slug' => 'payments.manage-subscriptions',
                    'description' => 'Can manage user subscriptions',
                ],
                [
                    'name' => 'View Revenue Reports',
                    'slug' => 'payments.view-reports',
                    'description' => 'Can view financial reports and analytics',
                ],
            ],

            // ==========================================
            // Role & Permission Management
            // ==========================================
            'roles' => [
                [
                    'name' => 'View Roles',
                    'slug' => 'roles.view',
                    'description' => 'Can view all roles',
                ],
                [
                    'name' => 'Create Roles',
                    'slug' => 'roles.create',
                    'description' => 'Can create new roles',
                ],
                [
                    'name' => 'Edit Roles',
                    'slug' => 'roles.edit',
                    'description' => 'Can edit existing roles',
                ],
                [
                    'name' => 'Delete Roles',
                    'slug' => 'roles.delete',
                    'description' => 'Can delete roles',
                ],
                [
                    'name' => 'Manage Permissions',
                    'slug' => 'roles.manage-permissions',
                    'description' => 'Can assign/remove permissions to roles',
                ],
            ],

            // ==========================================
            // Analytics & Reports
            // ==========================================
            'analytics' => [
                [
                    'name' => 'View Analytics',
                    'slug' => 'analytics.view',
                    'description' => 'Can view analytics dashboard',
                ],
                [
                    'name' => 'Export Data',
                    'slug' => 'analytics.export',
                    'description' => 'Can export reports and data',
                ],
            ],

            // ==========================================
            // System Settings
            // ==========================================
            'settings' => [
                [
                    'name' => 'View Settings',
                    'slug' => 'settings.view',
                    'description' => 'Can view system settings',
                ],
                [
                    'name' => 'Edit Settings',
                    'slug' => 'settings.edit',
                    'description' => 'Can modify system settings',
                ],
            ],
        ];

        // Create all permissions
        $this->command->info('Creating permissions...');
        
        foreach ($permissions as $group => $groupPermissions) {
            $this->command->info("  → {$group} permissions");
            
            foreach ($groupPermissions as $permissionData) {
                $permissionData['group'] = $group;
                
                Permission::updateOrCreate(
                    ['slug' => $permissionData['slug']],
                    $permissionData
                );
            }
        }

        $this->command->info('✓ Permissions created successfully!');
        
        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to roles based on their responsibilities.
     */
    private function assignPermissionsToRoles(): void
    {
        $this->command->info('');
        $this->command->info('Assigning permissions to roles...');

        // ==========================================
        // ADMIN - Full Access to Everything
        // ==========================================
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $allPermissions = Permission::all()->pluck('id')->toArray();
            $admin->permissions()->sync($allPermissions);
            $this->command->info('  ✓ Admin: All permissions assigned');
        }

        // ==========================================
        // MODERATOR - Content & User Management
        // ==========================================
        $moderator = Role::where('slug', 'moderator')->first();
        if ($moderator) {
            $moderatorPermissions = Permission::whereIn('slug', [
                // Movies
                'movies.view-all',
                'movies.create',
                'movies.edit',
                'movies.publish',
                'movies.manage-videos',
                // Genres
                'genres.view',
                'genres.create',
                'genres.edit',
                // Users (limited)
                'users.view',
                'users.edit',
                'users.ban',
                // Analytics
                'analytics.view',
            ])->pluck('id')->toArray();
            
            $moderator->permissions()->sync($moderatorPermissions);
            $this->command->info('  ✓ Moderator: Content management permissions assigned');
        }

        // ==========================================
        // CONTENT MANAGER - Movies & Genres Only
        // ==========================================
        $contentManager = Role::where('slug', 'content-manager')->first();
        if ($contentManager) {
            $contentPermissions = Permission::whereIn('slug', [
                // Movies
                'movies.view-all',
                'movies.create',
                'movies.edit',
                'movies.publish',
                'movies.manage-videos',
                // Genres
                'genres.view',
                'genres.create',
                'genres.edit',
                // Analytics (view only)
                'analytics.view',
            ])->pluck('id')->toArray();
            
            $contentManager->permissions()->sync($contentPermissions);
            $this->command->info('  ✓ Content Manager: Movie & genre permissions assigned');
        }

        // ==========================================
        // SUPPORT - Read Access for User Support
        // ==========================================
        $support = Role::where('slug', 'support')->first();
        if ($support) {
            $supportPermissions = Permission::whereIn('slug', [
                // Users (view only)
                'users.view',
                // Movies (view only)
                'movies.view-all',
                // Payments
                'payments.view',
                'payments.manage-subscriptions',
                // Analytics (view only)
                'analytics.view',
            ])->pluck('id')->toArray();
            
            $support->permissions()->sync($supportPermissions);
            $this->command->info('  ✓ Support: Customer support permissions assigned');
        }

        // ==========================================
        // USER - Basic Viewing Permissions
        // ==========================================
        $user = Role::where('slug', 'user')->first();
        if ($user) {
            $userPermissions = Permission::whereIn('slug', [
                'movies.view',
                'genres.view',
            ])->pluck('id')->toArray();
            
            $user->permissions()->sync($userPermissions);
            $this->command->info('  ✓ User: Basic viewing permissions assigned');
        }

        $this->command->info('');
        $this->command->info('🎉 RBAC system seeded successfully!');
        $this->command->newLine();
    }
}
