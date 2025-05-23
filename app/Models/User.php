<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Company> $companies
 * @property-read int|null $companies_count
 * @property-read Collection<int, CompanyUser> $companyUsers
 * @property-read int|null $company_users_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ─────────────────────────────────────
    // Mass Assignment & Serialization
    // ─────────────────────────────────────

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withTimestamps();
    }

    // ─────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────

    // Pivot table for user-to-company

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_user_role')
            ->withTimestamps();
    }

    // Direct link to the pivot model

    public function shifts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Shift::class,
            CompanyUser::class,
            'user_id',
            'company_user_id',
            'id',
            'id'
        );
    }

    // Roles via pivot

    public function getPermissionsForCompany(Company $company): \Illuminate\Support\Collection
    {
        $companyUser = $this->companyUsers()->where('company_id', $company->id)->first();

        if (!$companyUser) {
            return collect();
        }

        return $companyUser->permissions()->pluck('name');
    }

    // Permissions via pivot

    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUser::class);
    }

    // Indirect relationship to shifts via company_user

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'company_user_permission')
            ->withTimestamps();
    }

    /**
     * Check if the user has a specific permission in a specific company.
     *
     * @param string $permission The permission slugs to check for
     * @param int $companyId The company ID to check in
     * @return bool
     */
    public function hasPermissionInCompany(string $permission, int $companyId): bool
    {
        return $this->permissions()
            ->where('name', $permission)
            ->wherePivot('company_id', $companyId)
            ->exists();
    }


    // ─────────────────────────────────────
    // Custom Logic
    // ─────────────────────────────────────

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
