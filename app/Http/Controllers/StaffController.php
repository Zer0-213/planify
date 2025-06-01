<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function inviteStaff(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phoneNumber' => 'nullable|string|max:15',
        ]);
        /** @var User $user */
        $user = auth()->user();

        $companyUser = $user->companyUsers()->where('company_id', $user->companies()->first()->id)->first();

        $this->staffService->inviteStaffMember($companyUser, $validated);
        return redirect()->back()->with('success', 'Staff member invited successfully.');

    }
}
