<?php

namespace App\Services;

use App\Models\CompanyUser;
use App\Models\TimeOffRequest;
use Illuminate\Database\Eloquent\Collection;

class TimeOffRequestService
{

    public function getAllTimeOffRequests(CompanyUser $companyUser): Collection
    {
        return TimeOffRequest::where('company_user_id', $companyUser->id)->orderBy('created_at', 'desc')->get();
    }

    public function getAllTimeOffRequestsByCompany(int $companyId): Collection
    {
        return TimeOffRequest::whereRelation('companyUser', 'company_id', $companyId)
            ->with(['companyUser.user'])
            ->orderBy('created_at', 'desc')
            ->get();

    }


    public function requestTimeOff(array $request): void
    {
        TimeOffRequest::create($request);
    }

}
