<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\DeleteStaffRequest;
use App\Http\Requests\staff\UpdateStaffRequest;
use App\Services\StaffService;
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

        $request->authorize('update', [$companyUser]);

        $this->staffService->updateStaffMember($staffId, $request->validated());

        return redirect()->back()->with('success', 'Staff member updated successfully.');

    }

    public function deleteStaffMember(DeleteStaffRequest $request, int $staffId): RedirectResponse
    {
        $companyUser = auth()->user()->companyUsers()->first();

        $request->authorize('delete', [$companyUser]);

        $this->staffService->deleteStaffMember($staffId);

        return redirect()->back()->with('success', 'Staff member deleted successfully.');

    }

}
