<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserFromInviteRequest;
use App\Http\Requests\StaffInviteRequest;
use App\Models\User;
use App\Services\StaffInviteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Permission\Models\Role;

class StaffInviteController extends Controller
{

    public function __construct(public StaffInviteService $staffInviteService)
    {

    }

    /**
     * Accept an invitation to join the company.
     * This method checks the invite ID and token from the request, validates them and retrieves the invited details.
     * Details are passed to the frontend.
     * @param Request $request
     * @return RedirectResponse
     */
    public function acceptInvite(Request $request): RedirectResponse
    {
        $invitedKeys = $request->all([
            'invite_id',
            'invite_token',
        ]);

        if (empty($invitedKeys['invite_id']) || empty($invitedKeys['invite_token'])) {
            throw new UnauthorizedException();
        }

        $invitedUser = $this->staffInviteService->handleInvite((int)$invitedKeys['invite_id'], $invitedKeys['invite_token']);
        if ($invitedUser) {
            auth()->login($invitedUser->user);

            $company = $invitedUser->company;
            session()->flash('just_accepted_invite');
            session()->flash('company_id', $company->id);

            return redirect()->route('dashboard');
        }

        return redirect()->route('showInvitedUserForm', [
            'invite_id' => $invitedKeys['invite_id'],
            'invite_token' => $invitedKeys['invite_token'],
        ]);
    }

    public function createUserFromInvite(CreateUserFromInviteRequest $request, Role $role): RedirectResponse
    {

        $companyUser = $this->staffInviteService->createStaffFromInvite($request->validated(), $role);

        auth()->login($companyUser->user);

        session()->flash('just_accepted_invite');
        session()->flash('company_id', $companyUser->company_id);

        return redirect()->route('dashboard');
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

        $this->staffInviteService->inviteStaffMember($companyUser, $request->validated());

        return redirect()->back()->with('success', 'Staff member invited successfully.');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function showInvitedUserForm(Request $request): Response
    {

        $inviteId = $request->query('invite_id');
        $inviteToken = $request->query('invite_token');

        if (empty($inviteId) || empty($inviteToken)) {
            throw new UnauthorizedException('Invite ID and token are required.');
        }

        $this->staffInviteService->validateInvitation($inviteId, $inviteToken);

        return Inertia::render('auth/AcceptInvite', [
            'invite_id' => request()->get('invite_id'),
            'invite_token' => request()->get('invite_token'),
        ]);
    }

}
