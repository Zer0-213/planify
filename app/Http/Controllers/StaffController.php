<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\CreateStaffRequest;
use App\Http\Requests\StaffInviteRequest;
use App\Models\CompanyInvite;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
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
            'roles' => array_map(static fn(RoleEnum $role) => $role->value, RoleEnum::cases()),
        ]);
    }

    public function create(CreateStaffRequest $request): RedirectResponse
    {

        $this->staffService->addStaffMember($request->validated(), auth()->user()->companies()->first());

        return redirect()->back()->with('success', 'Staff member created successfully.');

    }

    /**
     * Invite a staff member to the company.
     *
     * @param StaffInviteRequest $request
     * @return RedirectResponse
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

    /**
     * Accept an invitation to join the company.
     * This method checks the invite ID and token from the request, validates them and retrieves the invited details.
     * Details are passed to frontend.
     *
     * @param Request $request
     * @param CompanyInvite $companyInvite
     * @return Response
     */
    public function acceptedInvited(Request $request, CompanyInvite $companyInvite): Response
    {
        $invitedKeys = $request->query([
            'invite_id',
            'token',
        ]);

        if (empty($invitedKeys['invite_id']) || empty($invitedKeys['token'])) {
            throw new UnauthorizedException();
        }

        $invitedUser = $this->staffService->checkAndGetInvitedUser((int)$invitedKeys['invite_id'], $invitedKeys['token'], $companyInvite);
        return Inertia::render('staff/StaffInviteAccept', [
            'inviteId' => $invitedUser->id,
            'email' => $invitedUser->email,
            'name' => $invitedUser->name,
            'phoneNumber' => $invitedUser->phone_number,
            'companyId' => $invitedUser->company_id,
        ]);
    }
}
