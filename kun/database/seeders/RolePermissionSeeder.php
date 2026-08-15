<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions for Movie Streaming Platform
        $permissions = [
            // Dashboard Permissions
            [
                'name' => 'Access Admin Dashboard',
                'slug' => 'dashboard.admin',
                'description' => 'Can access admin dashboard',
                'group' => 'Dashboard',
            ],
            [
                'name' => 'Access Moderator Dashboard',
                'slug' => 'dashboard.moderator',
                'description' => 'Can access moderator dashboard',
                'group' => 'Dashboard',
            ],
            [
                'name' => 'Access User Dashboard',
                'slug' => 'dashboard.user',
                'description' => 'Can access user dashboard',
                'group' => 'Dashboard',
            ],

            // Movie Permissions
            [
                'name' => 'View Movies',
                'slug' => 'movie.read',
                'description' => 'Can view movies list',
                'group' => 'Movies',
            ],
            [
                'name' => 'Create Movie',
                'slug' => 'movie.create',
                'description' => 'Can create new movies',
                'group' => 'Movies',
            ],
            [
                'name' => 'Edit Movie',
                'slug' => 'movie.update',
                'description' => 'Can edit movie details',
                'group' => 'Movies',
            ],
            [
                'name' => 'Delete Movie',
                'slug' => 'movie.delete',
                'description' => 'Can delete movies',
                'group' => 'Movies',
            ],
            [
                'name' => 'Publish Movie',
                'slug' => 'movie.publish',
                'description' => 'Can publish/unpublish movies',
                'group' => 'Movies',
            ],
            [
                'name' => 'Manage Movie Videos',
                'slug' => 'movie.videos',
                'description' => 'Can upload/manage movie videos',
                'group' => 'Movies',
            ],

            // Genre Permissions
            [
                'name' => 'View Genres',
                'slug' => 'genre.read',
                'description' => 'Can view genres list',
                'group' => 'Genres',
            ],
            [
                'name' => 'Create Genre',
                'slug' => 'genre.create',
                'description' => 'Can create new genres',
                'group' => 'Genres',
            ],
            [
                'name' => 'Edit Genre',
                'slug' => 'genre.update',
                'description' => 'Can edit genre details',
                'group' => 'Genres',
            ],
            [
                'name' => 'Delete Genre',
                'slug' => 'genre.delete',
                'description' => 'Can delete genres',
                'group' => 'Genres',
            ],

            // User Management Permissions
            [
                'name' => 'View Users',
                'slug' => 'user.read',
                'description' => 'Can view users list',
                'group' => 'Users',
            ],
            [
                'name' => 'Create User',
                'slug' => 'user.create',
                'description' => 'Can create new users',
                'group' => 'Users',
            ],
            [
                'name' => 'Edit User',
                'slug' => 'user.update',
                'description' => 'Can edit user details',
                'group' => 'Users',
            ],
            [
                'name' => 'Delete User',
                'slug' => 'user.delete',
                'description' => 'Can delete users',
                'group' => 'Users',
            ],
            [
                'name' => 'Suspend User',
                'slug' => 'user.suspend',
                'description' => 'Can suspend/unsuspend users',
                'group' => 'Users',
            ],
            [
                'name' => 'Assign Roles',
                'slug' => 'user.assign-roles',
                'description' => 'Can assign roles to users',
                'group' => 'Users',
            ],

            // Role Permissions
            [
                'name' => 'View Roles',
                'slug' => 'role.read',
                'description' => 'Can view roles list',
                'group' => 'Roles',
            ],
            [
                'name' => 'Create Role',
                'slug' => 'role.create',
                'description' => 'Can create new roles',
                'group' => 'Roles',
            ],
            [
                'name' => 'Edit Role',
                'slug' => 'role.update',
                'description' => 'Can edit role details',
                'group' => 'Roles',
            ],
            [
                'name' => 'Delete Role',
                'slug' => 'role.delete',
                'description' => 'Can delete roles',
                'group' => 'Roles',
            ],

            // Permission Management
            [
                'name' => 'View Permissions',
                'slug' => 'permission.read',
                'description' => 'Can view permissions list',
                'group' => 'Permissions',
            ],
            [
                'name' => 'Create Permission',
                'slug' => 'permission.create',
                'description' => 'Can create new permissions',
                'group' => 'Permissions',
            ],
            [
                'name' => 'Edit Permission',
                'slug' => 'permission.update',
                'description' => 'Can edit permission details',
                'group' => 'Permissions',
            ],
            [
                'name' => 'Delete Permission',
                'slug' => 'permission.delete',
                'description' => 'Can delete permissions',
                'group' => 'Permissions',
            ],

            // Payment & Subscription Permissions
            [
                'name' => 'View Payments',
                'slug' => 'payment.read',
                'description' => 'Can view payment history',
                'group' => 'Payments',
            ],
            [
                'name' => 'Process Refund',
                'slug' => 'payment.refund',
                'description' => 'Can process payment refunds',
                'group' => 'Payments',
            ],
            [
                'name' => 'Manage Subscriptions',
                'slug' => 'subscription.manage',
                'description' => 'Can manage user subscriptions',
                'group' => 'Payments',
            ],

            // Content Moderation
            [
                'name' => 'Moderate Reviews',
                'slug' => 'review.moderate',
                'description' => 'Can moderate user reviews',
                'group' => 'Moderation',
            ],
            [
                'name' => 'Moderate Comments',
                'slug' => 'comment.moderate',
                'description' => 'Can moderate user comments',
                'group' => 'Moderation',
            ],

            // Analytics & Reports
            [
                'name' => 'View Analytics',
                'slug' => 'analytics.read',
                'description' => 'Can view platform analytics',
                'group' => 'Analytics',
            ],
            [
                'name' => 'Export Reports',
                'slug' => 'report.export',
                'description' => 'Can export system reports',
                'group' => 'Analytics',
            ],

            // Settings
            [
                'name' => 'Manage Settings',
                'slug' => 'settings.manage',
                'description' => 'Can manage system settings',
                'group' => 'Settings',
            ],
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('✅ Permissions created successfully!');

        // Create Roles
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full access',
                'permissions' => 'all', // All permissions
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content-manager',
                'description' => 'Can manage movies and genres',
                'permissions' => [
                    'dashboard.admin',
                    'movie.read', 'movie.create', 'movie.update', 'movie.delete', 'movie.publish', 'movie.videos',
                    'genre.read', 'genre.create', 'genre.update', 'genre.delete',
                    'analytics.read',
                ],
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Can moderate content and manage users',
                'permissions' => [
                    'dashboard.moderator',
                    'movie.read', 'movie.update',
                    'genre.read',
                    'user.read', 'user.suspend',
                    'review.moderate', 'comment.moderate',
                    'analytics.read',
                ],
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular user with basic access',
                'permissions' => [
                    'dashboard.user',
                ],
            ],
            [
                'name' => 'Premium User',
                'slug' => 'premium-user',
                'description' => 'Premium subscriber with extended access',
                'permissions' => [
                    'dashboard.user',
                ],
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                ]
            );

            // Assign permissions
            if ($roleData['permissions'] === 'all') {
                $role->permissions()->sync(Permission::all()->pluck('id'));
            } else {
                $permissionIds = Permission::whereIn('slug', $roleData['permissions'])->pluck('id');
                $role->permissions()->sync($permissionIds);
            }

            $this->command->info("✅ Role '{$role->name}' created with " . $role->permissions()->count() . " permissions");
        }

        $this->command->info('🎉 All roles and permissions created successfully!');
    }
}
