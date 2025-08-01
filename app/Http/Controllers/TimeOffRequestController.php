<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\StoreTimeOffRequest;
use App\Models\TimeOffRequest;
use App\Services\TimeOffRequestService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TimeOffRequestController extends Controller
{

    public function __construct(private readonly TimeOffRequestService $timeOffRequestService)
    {

    }

    public function index(Request $request): Response
    {
        $companyUser = $request->user()->companyUsers()->first();
        $userTimeOff = $this->timeOffRequestService->getAllTimeOffRequests($companyUser);
        $upcomingTimeOff = $this->timeOffRequestService->getApprovedUpcomingTimeOff($companyUser->company);

        $pendingRequests = null;
        if ($companyUser->hasPermissionTo(PermissionEnum::MANAGE_TIME_OFF_REQUESTS)) {
            $pendingRequests = $this->timeOffRequestService->getPendingRequestsForApproval($companyUser);
        }

        return Inertia::render('timeOff/TimeOffMain', [
            'userTimeOff' => $userTimeOff->toArray(),
            'upcomingTimeOff' => $upcomingTimeOff->toArray(),
            'pendingRequests' => $pendingRequests?->toArray(),
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

        $data = $request->getTimeOffData($companyUser->id);

        $this->timeOffRequestService->requestTimeOff($data);

        return redirect()->back()->with('success', 'Time off request submitted successfully.');
    }

    /**
     * Updates a time off request
     *
     * @throws AuthorizationException
     */
    public function updateTimeOff(StoreTimeOffRequest $request, TimeOffRequest $timeOffRequest): RedirectResponse
    {
        $companyUser = $request->user()->companyUsers()->first();

        if (!$companyUser->hasPermissionTo(PermissionEnum::MANAGE_TIME_OFF_REQUESTS)) {
            throw new AuthorizationException('You do not have permission to manage time off requests.');
        }

        $data = $request->getTimeOffData($companyUser->id);

        $this->timeOffRequestService->updateTimeOffRequest($data, $timeOffRequest);

        return redirect()->route('time-off.index')->with('success', 'Time off request updated successfully.');
    }

    /**
     * Delete a time off request
     *
     */
    public function deleteTimeOff(Request $request, TimeOffRequest $timeOffRequest): RedirectResponse
    {
        $this->timeOffRequestService->deleteTimeOffRequest($timeOffRequest);

        return redirect()->route('time-off.index')->with('success', 'Time off request deleted successfully.');
    }
}
