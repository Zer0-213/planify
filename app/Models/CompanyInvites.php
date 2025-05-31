<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class CompanyInvites
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
 */
class CompanyInvites extends Model
{
    protected $fillable = [
        'company_id',
        'email',
        'name',
        'token',
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
}
