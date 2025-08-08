<?php

namespace App\Models;

use App\Enums\PermissionEnum;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string|null $phone_number
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Company> $companies
 * @property-read int|null $companies_count
 * @property-read Collection<int, CompanyInvite> $companyInvites
 * @property-read int|null $company_invites_count
 * @property-read Collection<int, CompanyUser> $companyUsers
 * @property-read int|null $company_users_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Role|null $roles
 * @property-read Collection<int, Shift> $shifts
 * @property-read int|null $shifts_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User wherePhoneNumber($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // ─────────────────────────────────────
    // Mass Assignment & Serialization
    // ─────────────────────────────────────

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'wage'
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

    // Link to company_invites
    public function companyInvites(): HasMany
    {
        return $this->hasMany(CompanyInvite::class, 'invited_by');
    }

    // Pivot table for user-to-company

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

    // Direct link to the pivot model
    public function getPermissionsForCompany(Company $company): \Illuminate\Support\Collection
    {
        $companyUser = $this->companyUsers()->where('company_id', $company->id)->first();

        if (!$companyUser) {
            return collect();
        }

        return $companyUser->permissions()->pluck('name');
    }


    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUser::class);
    }

    // Permissions via pivot
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'company_user_permission');
    }

    // Indirect relationship to shifts via company_user

    /**
     * Check if the user has a specific permission in a specific company.
     *
     * @param PermissionEnum $permission The permission slugs to check for
     * @param int $companyId The company ID to check in
     * @return bool
     */
    public function hasPermissionInCompany(PermissionEnum $permission, int $companyId): bool
    {
        $companyUser = $this->companyUsers()->where('company_id', $companyId)->first();

        if (!$companyUser) {
            return false;
        }

        $roles = $this->roles()->where('company_id', $companyId)->with('permissions')->get();


        foreach ($roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }

        return false;

    }


    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'company_user_role');
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
