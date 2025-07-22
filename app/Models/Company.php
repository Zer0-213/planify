<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property int $owner_id
 * @property string|null $phone_number
 * @property string $type
 * @property-read Collection<int, CompanyUser> $companyUsers
 * @property-read int|null $company_users_count
 * @property-read Collection<int, CompanyUser> $companyUsersWithShifts
 * @property-read int|null $company_users_with_shifts_count
 * @property-read Collection<int, CompanyInvite> $invites
 * @property-read int|null $invites_count
 * @property-read User|null $owner
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static CompanyFactory factory($count = null, $state = [])
 * @method static Builder<static>|Company newModelQuery()
 * @method static Builder<static>|Company newQuery()
 * @method static Builder<static>|Company query()
 * @method static Builder<static>|Company whereCreatedAt($value)
 * @method static Builder<static>|Company whereId($value)
 * @method static Builder<static>|Company whereName($value)
 * @method static Builder<static>|Company whereOwnerId($value)
 * @method static Builder<static>|Company wherePhoneNumber($value)
 * @method static Builder<static>|Company whereType($value)
 * @method static Builder<static>|Company whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Company name
        'owner_id', // Owner user ID
        'phone_number', // Company phone number
        'type', // Company type (e.g., Retail)
    ];

    /**
     * Get the user that owns the company (the company creator).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the users associated with the company.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withTimestamps();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(CompanyInvite::class);
    }

    public function companyUsersWithShifts(): HasMany
    {
        return $this->companyUsers()->with(['user', 'shifts']);
    }

    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUser::class);
    }
}

