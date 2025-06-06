<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin Builder
 * @property int $company_id
 * @property int $user_id
 * * @property-read Company $company
 * * @property-read User $user
 */
class CompanyUser extends Model
{
    use HasFactory;
    use HasRoles;

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
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_user_roles');
    }

    /**
     * Get the permissions for this company-user relationship.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'company_user_permissions');
    }
}
