<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
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
        'group',
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
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')
            ->withTimestamps();
    }

    // ==========================================
    // Scopes
    // ==========================================

    /**
     * Scope to get permission by slug.
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
     * Scope to get permissions by group.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to get permissions with roles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRoles($query)
    {
        return $query->with('roles');
    }

    // ==========================================
    // Static Helper Methods
    // ==========================================

    /**
     * Find permission by name.
     *
     * @param string $name
     * @return Permission|null
     */
    public static function findByName(string $name): ?Permission
    {
        return static::where('name', $name)->first();
    }

    /**
     * Find permission by slug.
     *
     * @param string $slug
     * @return Permission|null
     */
    public static function findBySlug(string $slug): ?Permission
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get all permissions grouped by category.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllGrouped()
    {
        return static::all()->groupBy('group');
    }
}

