<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\Staff\DeleteStaffRequest;
use App\Http\Requests\staff\UpdateStaffRequest;
use App\Services\StaffService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function __construct(public StaffService $staffService)
    {
    }

    public function index(Role $roles): Response
    {
        $staffMembers = $this->staffService->getStaffMembers(auth()->user()->companies()->first());

        return Inertia::render('staff/StaffMain', [
            'staffMembers' => $staffMembers,
            'roles' => $roles->all(),
        ]);
    }

    public function updateStaffMember(UpdateStaffRequest $request, int $staffId): RedirectResponse
    {
        $companyUser = auth()->user()->companyUsers()->first();

        if (!$companyUser->hasPermission(PermissionEnum::UPDATE_STAFF_MEMBER)) {
            throw new AuthorizationException('You do not have permission to update this staff member.');
        }

        $this->staffService->updateStaffMember($staffId, $request->validated());

        return redirect()->back()->with('success', 'Staff member updated successfully.');

    }

    public function deleteStaffMember(DeleteStaffRequest $request, int $staffId): RedirectResponse
    {
        $companyUser = auth()->user()->companyUsers()->first();

        if (!$companyUser->hasPermission(PermissionEnum::DELETE_STAFF_MEMBER)) {
            throw new AuthorizationException('You do not have permission to delete this staff member.');
        }

        $this->staffService->deleteStaffMember($staffId);

        return redirect()->back()->with('success', 'Staff member deleted successfully.');

    }

}
