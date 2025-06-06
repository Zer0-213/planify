<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property string $name
 * @property string $token
 * @property int $invited_by
 * @property Carbon|null $expires_at
 * @property string|null $phone_number
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
