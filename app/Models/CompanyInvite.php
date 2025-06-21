<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

/**
 * Class CompanyInvite
 * 
 * Represents an invitation to join a company.
 *
 * @property int $id
 * @property int $company_id
 * @property string $email
 * @property string|null $phone_number
 * @property string $name
 * @property string $token
 * @property int|null $wage
 * @property int $role_id
 * @property int $invited_by
 * @property Carbon|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\User $inviter
 * @property-read Role $role
 * @method static \Database\Factories\CompanyInviteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereInvitedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyInvite whereWage($value)
 * @mixin \Eloquent
 */
class CompanyInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone_number',
        'token',
        'wage',
        'role_id',
        'invited_by',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function hasExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
