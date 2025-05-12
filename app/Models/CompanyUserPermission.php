<?php

// app/Models/CompanyUserPermission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $company_user_id
 * @property int $permission_id
 * @property-read \App\Models\CompanyUser $companyUser
 * @property-read \App\Models\Permission $permission
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission whereCompanyUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyUserPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompanyUserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_user_id',
        'permission_id',
    ];

    /**
     * Get the company user associated with the permission.
     */
    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }

    /**
     * Get the permission associated with the company user.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
