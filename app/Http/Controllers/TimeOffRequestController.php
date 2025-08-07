<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\StoreTimeOffRequest;
use App\Http\Requests\UpdateTimeOffRequest;
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
        $companyUser = getCurrentCompanyUser($request);

        $search = $request->string('q')->toString();
        $upcomingTimeOff = $this->timeOffRequestService->getApprovedUpcomingTimeOff($companyUser->company, $search ?: null);

        $pendingRequests = null;
        if ($companyUser->hasPermissionTo(PermissionEnum::MANAGE_TIME_OFF_REQUESTS)) {
            $pendingRequests = $this->timeOffRequestService->getPendingRequestsForApproval($companyUser);
        }

        return Inertia::render('timeOff/TimeOffMain', [
            'upcomingTimeOff' => $upcomingTimeOff->toArray(),
            'pendingRequests' => $pendingRequests?->toArray(),
            'filters' => [
                'q' => $search,
            ],
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function requestTimeOff(StoreTimeOffRequest $request): RedirectResponse
    {
        $companyUser = getCurrentCompanyUser($request);
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
        $companyUser = getCurrentCompanyUser($request);

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
        if ($timeOffRequest->companyUser->id !== getCurrentCompanyUser($request)->id) {
            throw new AuthorizationException('You do not have permission to delete this time off request.');
        }

        $this->timeOffRequestService->deleteTimeOffRequest($timeOffRequest);

        return redirect()->route('time-off.index')->with('success', 'Time off request deleted successfully.');
    }

    /**
     * Respond to a time off request (approve or reject)
     *
     * @throws AuthorizationException
     */
    public function respondToTimeOff(UpdateTimeOffRequest $request, TimeOffRequest $timeOffRequest): RedirectResponse
    {
        $companyUser = getCurrentCompanyUser($request);

        if (!$companyUser->hasPermissionTo(PermissionEnum::MANAGE_TIME_OFF_REQUESTS)) {
            throw new AuthorizationException('You do not have permission to manage time off requests.');
        }

        if ($timeOffRequest->companyUser->id === $companyUser->id) {
            throw new AuthorizationException('You cannot approve or reject your own time off request.');
        }

        $status = $request->input('status');
        $adminNotes = $request->input('admin_notes');

        $data = [
            'status' => $status,
            'admin_notes' => $adminNotes,
            'approved_by' => $companyUser->id,
            'approved_at' => now(),
        ];

        $this->timeOffRequestService->updateTimeOffRequest($data, $timeOffRequest, true);

        $message = 'Time off request' . $status === 'approved' ? 'approved' : 'rejected' . 'successfully.';

        return redirect()->back()->with('success', $message);
    }
}
