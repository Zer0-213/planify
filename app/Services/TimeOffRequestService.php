<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\TimeOffRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TimeOffRequestService
{

    public function getAllTimeOffRequests(CompanyUser $companyUser): Collection
    {
        return TimeOffRequest::where('company_user_id', $companyUser->id)
            ->whereDate('start_date', '>=', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPendingRequestsForApproval(CompanyUser $viewer): Collection
    {
        return TimeOffRequest::with(['companyUser.user'])
            ->whereNot('company_user_id', $viewer->id)
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function getApprovedUpcomingTimeOff(Company $company): LengthAwarePaginator
    {
        return TimeOffRequest::with(['companyUser.user'])
            ->whereRelation('companyUser', 'company_id', $company->id)
            ->where('status', 'approved')
            ->where('end_date', '>=', now()->toDateString())
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->paginate(15);

    }

    public function requestTimeOff(array $request): void
    {
        TimeOffRequest::create($request);
    }

    public function updateTimeOffRequest(array $request, TimeOffRequest $timeOffRequest): void
    {
        $timeOffRequest->update($request);
    }

    public function deleteTimeOffRequest(TimeOffRequest $timeOffRequest): void
    {
        if ($timeOffRequest->isApproved()) {
            $timeOffRequest->update(['cancellation_status' => 'requested']);
        } else {
            $timeOffRequest->delete();
        }

    }

}
