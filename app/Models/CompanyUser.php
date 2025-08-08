<?php

namespace App\Models;

use App\Enums\PermissionEnum;
use Database\Factories\CompanyUserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $company_id
 * @property int $user_id
 * @property int $wage
 * @property-read Company $company
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, Shift> $shifts
 * @property-read int|null $shifts_count
 * @property-read User $user
 * @method static CompanyUserFactory factory($count = null, $state = [])
 * @method static Builder<static>|CompanyUser newModelQuery()
 * @method static Builder<static>|CompanyUser newQuery()
 * @method static Builder<static>|CompanyUser permission($permissions, $without = false)
 * @method static Builder<static>|CompanyUser query()
 * @method static Builder<static>|CompanyUser role($roles, $guard = null, $without = false)
 * @method static Builder<static>|CompanyUser whereCompanyId($value)
 * @method static Builder<static>|CompanyUser whereCreatedAt($value)
 * @method static Builder<static>|CompanyUser whereId($value)
 * @method static Builder<static>|CompanyUser whereUpdatedAt($value)
 * @method static Builder<static>|CompanyUser whereUserId($value)
 * @method static Builder<static>|CompanyUser whereWage($value)
 * @method static Builder<static>|CompanyUser withoutPermission($permissions)
 * @method static Builder<static>|CompanyUser withoutRole($roles, $guard = null)
 * @mixin Eloquent
 */
class CompanyUser extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
    ];

    protected string $guard_name = 'web';

    /**
     * Get the company associated with the pivot.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user associated with the pivot.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shifts for this company-user relationship.
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    /**
     * Get the roles for this company-user relationship.
     * This overrides the HasRoles trait method to use a custom pivot table.
     *
     * @return BelongsToMany<Role>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_user_roles');
    }

    /**
     * Get the permissions for this company-user relationship.
     *
     * @return BelongsToMany<Permission>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'company_user_permissions');
    }

    /**
     * Check if this company user has a specific permission.
     *
     * @param PermissionEnum $permission The permission name to check for
     * @return bool
     */
    public function hasPermission(PermissionEnum $permission): bool
    {
        return $this->hasPermissionTo($permission->value);

    }
}
