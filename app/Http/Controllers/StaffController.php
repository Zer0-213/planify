<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffInviteRequest;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    public function __construct(public StaffService $staffService)
    {
    }

    public function index(): Response
    {
        $staffMembers = $this->staffService->getStaffMembers(auth()->user()->companies()->first());

        return Inertia::render('staff/StaffMain', [
            'staffMembers' => $staffMembers,
        ]);
    }

    /**
     * Invite a staff member to the company.
     *
     * @param StaffInviteRequest $request
     * @throws ValidationException
     */
    public function inviteStaff(StaffInviteRequest $request): RedirectResponse
    {

        /** @var User $user */
        $user = auth()->user();

        $companyUser = $user->companyUsers()->where('company_id', $user->companies()->first()->id)->first();

        $this->staffService->inviteStaffMember($companyUser, $request->validated());
        return redirect()->back()->with('success', 'Staff member invited successfully.');

    }
}
