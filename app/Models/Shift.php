<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_user_id',
        'role_id',
        'shift_date',
        'starts_at',
        'ends_at',
        'break_duration',
        'location',
        'notes',
        'status'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'shift_date' => 'date',
        'break_duration' => 'integer',
    ];

    protected $attributes = [
        'status' => 'scheduled'
    ];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
