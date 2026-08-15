<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\User;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Permission;
use App\Policies\MoviePolicy;
use App\Policies\GenrePolicy;
use App\Policies\UserPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Movie::class => MoviePolicy::class,
        Genre::class => GenrePolicy::class,
        User::class => UserPolicy::class,
        Payment::class => PaymentPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Define super-admin gate
        Gate::before(function ($user, $ability) {
            // Super admins bypass all gate checks
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
