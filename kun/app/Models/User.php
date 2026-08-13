<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'avatar',
        'subscription_plan',
        'subscription_status',
        'subscription_start',
        'subscription_end',
        'last_payment_id',
        'email_notifications',
        'auto_play_next',
        'video_quality',
        'auto_renew',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'subscription_start' => 'datetime',
            'subscription_end' => 'datetime',
            'email_notifications' => 'boolean',
            'auto_play_next' => 'boolean',
            'auto_renew' => 'boolean',
        ];
    }

    // ==========================================
    // RBAC Relationships
    // ==========================================

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * Get permissions through roles.
     */
    public function permissions(): array
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->pluck('name')->unique()->toArray();
    }

    // ==========================================
    // RBAC Helper Methods
    // ==========================================

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $role): bool
    {
        if (is_array($role)) {
            return $this->roles->whereIn('name', $role)->isNotEmpty();
        }
        return $this->roles->contains('name', $role);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string|array $permission): bool
    {
        if (is_array($permission)) {
            $userPermissions = $this->permissions();
            foreach ($permission as $perm) {
                if (in_array($perm, $userPermissions)) {
                    return true;
                }
            }
            return false;
        }
        return in_array($permission, $this->permissions());
    }

    /**
     * Check if user has all given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $userPermissions = $this->permissions();
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is moderator.
     */
    public function isModerator(): bool
    {
        return $this->hasRole('moderator');
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(string|int|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        } elseif (is_int($role)) {
            $role = Role::findOrFail($role);
        }

        if (!$this->roles->contains($role->id)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(string|int|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        } elseif (is_int($role)) {
            $role = Role::findOrFail($role);
        }

        $this->roles()->detach($role->id);
    }

    /**
     * Sync roles for the user.
     */
    public function syncRoles(array $roles): void
    {
        $roleIds = collect($roles)->map(function ($role) {
            if ($role instanceof Role) {
                return $role->id;
            }
            if (is_numeric($role)) {
                return $role;
            }
            return Role::where('name', $role)->firstOrFail()->id;
        })->toArray();

        $this->roles()->sync($roleIds);
    }

    // ==========================================
    // Other Relationships
    // ==========================================

    /**
     * Get user's movie views (watch history).
     */
    public function movieViews(): HasMany
    {
        return $this->hasMany(MovieView::class);
    }

    /**
     * Get user's favorite movies.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get user's watchlist.
     */
    public function watchlist(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Get user's payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ==========================================
    // Subscription Helper Methods
    // ==========================================

    /**
     * Check if user has active premium subscription.
     */
    public function isPremium(): bool
    {
        return $this->subscription_status === 'active' 
            && $this->subscription_end 
            && $this->subscription_end->isFuture();
    }

    /**
     * Check if subscription has expired.
     */
    public function subscriptionExpired(): bool
    {
        return $this->subscription_end && $this->subscription_end->isPast();
    }

    /**
     * Get days remaining in subscription.
     */
    public function subscriptionDaysRemaining(): int
    {
        if (!$this->subscription_end) {
            return 0;
        }
        return max(0, now()->diffInDays($this->subscription_end, false));
    }

    /**
     * Get avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=e50914&color=fff&size=200';
    }
}
