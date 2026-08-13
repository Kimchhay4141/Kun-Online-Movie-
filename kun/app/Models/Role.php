<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Check if role has a specific permission.
     *
     * @param string|array $permission
     * @return bool
     */
    public function hasPermission(string|array $permission): bool
    {
        if (is_array($permission)) {
            return $this->permissions->whereIn('name', $permission)->isNotEmpty();
        }

        return $this->permissions->contains('name', $permission);
    }

    /**
     * Assign a permission to the role.
     *
     * @param string|int|Permission $permission
     * @return void
     */
    public function assignPermission(string|int|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        } elseif (is_int($permission)) {
            $permission = Permission::findOrFail($permission);
        }

        if (!$this->permissions->contains($permission->id)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Remove a permission from the role.
     *
     * @param string|int|Permission $permission
     * @return void
     */
    public function removePermission(string|int|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        } elseif (is_int($permission)) {
            $permission = Permission::findOrFail($permission);
        }

        $this->permissions()->detach($permission->id);
    }

    /**
     * Sync permissions for the role.
     *
     * @param array $permissions
     * @return void
     */
    public function syncPermissions(array $permissions): void
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            if ($permission instanceof Permission) {
                return $permission->id;
            }
            if (is_numeric($permission)) {
                return $permission;
            }
            return Permission::where('name', $permission)->firstOrFail()->id;
        })->toArray();

        $this->permissions()->sync($permissionIds);
    }

    // ==========================================
    // Scopes
    // ==========================================

    /**
     * Scope to get role by slug.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Scope to get roles with permissions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPermissions($query)
    {
        return $query->with('permissions');
    }

    // ==========================================
    // Static Helper Methods
    // ==========================================

    /**
     * Find role by name.
     *
     * @param string $name
     * @return Role|null
     */
    public static function findByName(string $name): ?Role
    {
        return static::where('name', $name)->first();
    }

    /**
     * Find role by slug.
     *
     * @param string $slug
     * @return Role|null
     */
    public static function findBySlug(string $slug): ?Role
    {
        return static::where('slug', $slug)->first();
    }
}
