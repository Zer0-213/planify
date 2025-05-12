<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $company_user_id
 * @property int $role_id
 * @method static Builder<static>|CompanyUserRole newModelQuery()
 * @method static Builder<static>|CompanyUserRole newQuery()
 * @method static Builder<static>|CompanyUserRole query()
 * @method static Builder<static>|CompanyUserRole whereCompanyUserId($value)
 * @method static Builder<static>|CompanyUserRole whereCreatedAt($value)
 * @method static Builder<static>|CompanyUserRole whereId($value)
 * @method static Builder<static>|CompanyUserRole whereRoleId($value)
 * @method static Builder<static>|CompanyUserRole whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CompanyUserRole extends Model
{
    //
}
