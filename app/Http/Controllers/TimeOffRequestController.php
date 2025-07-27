<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\StoreTimeOffRequest;
use App\Services\TimeOffRequestService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Request;

class TimeOffRequestController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly TimeOffRequestService $timeOffRequestService)
    {

    }

    public function index(Request $request): Response
    {
        $companyUser = $request->user()->companyUsers()->first();
        $userTimeOff = $this->timeOffRequestService->getAllTimeOffRequests($companyUser);

        $companyTimeOffRequests = null;
        if ($companyUser->hasPermissionTo(PermissionEnum::MANAGE_TIME_OFF_REQUESTS)) {
            $companyTimeOffRequests = $this->timeOffRequestService->getAllTimeOffRequestsByCompany($companyUser->companyId);
        }

        return Inertia::render('timeOff/TimeOffMain', [
            'userTimeOff' => $userTimeOff->toArray(),
            'companyTimeOffRequests' => $companyTimeOffRequests->toArray(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function requestTimeOff(StoreTimeOffRequest $request): RedirectResponse
    {
        $companyUser = $request->user()->companyUsers()->first();
        if (!$companyUser->hasPermissionTo(PermissionEnum::REQUEST_TIME_OFF)) {
            throw new AuthorizationException('You do not have permission to request time off.');
        }

        $isFullDay = !$request->filled('start_time') && !$request->filled('end_time');

        $data = [
            'company_user_id' => $companyUser->id,
            'start_date' => $request->input('start_date'),
            'start_time' => $request->input('start_time'),
            'end_date' => $request->input('end_date'),
            'end_time' => $request->input('end_time'),
            'is_full_day' => $isFullDay,
            'reason' => $request->input('reason'),
        ];

        $this->timeOffRequestService->requestTimeOff($data);

        return redirect()->back()->with('success', 'Time off request submitted successfully.');
    }
}
