<?php

namespace App\Models;

use Database\Factories\ShiftFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $company_user_id
 * @property Carbon $shift_date
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property int|null $break_duration
 * @property string|null $notes
 * @property int|null $role_id
 * @property string|null $location
 * @property string $status
 * @property-read CompanyUser $companyUser
 * @method static ShiftFactory factory($count = null, $state = [])
 * @method static Builder<static>|Shift newModelQuery()
 * @method static Builder<static>|Shift newQuery()
 * @method static Builder<static>|Shift query()
 * @method static Builder<static>|Shift whereBreakDuration($value)
 * @method static Builder<static>|Shift whereCompanyUserId($value)
 * @method static Builder<static>|Shift whereCreatedAt($value)
 * @method static Builder<static>|Shift whereEndsAt($value)
 * @method static Builder<static>|Shift whereId($value)
 * @method static Builder<static>|Shift whereLocation($value)
 * @method static Builder<static>|Shift whereNotes($value)
 * @method static Builder<static>|Shift whereRoleId($value)
 * @method static Builder<static>|Shift whereShiftDate($value)
 * @method static Builder<static>|Shift whereStartsAt($value)
 * @method static Builder<static>|Shift whereStatus($value)
 * @method static Builder<static>|Shift whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Shift extends Model
{
    use HasFactory, SoftDeletes;

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
