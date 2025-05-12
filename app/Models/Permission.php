<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string $label
 * @property string|null $description
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission whereCreatedAt($value)
 * @method static Builder<static>|Permission whereDescription($value)
 * @method static Builder<static>|Permission whereId($value)
 * @method static Builder<static>|Permission whereLabel($value)
 * @method static Builder<static>|Permission whereName($value)
 * @method static Builder<static>|Permission whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // e.g., edit_schedule, view_reports
        'label', // Human-readable name (e.g., "Edit Schedule")
    ];

    /**
     * Get the roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->withTimestamps();
    }

    /**
     * Get the users that have this permission in a company.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user_permission')
            ->withTimestamps();
    }

    public function companyUsers(): BelongsToMany
    {
        return $this->belongsToMany(CompanyUser::class, 'company_user_permissions');

    }
}

