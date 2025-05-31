<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Log;
use Nette\Schema\ValidationException;

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
        $compannyUser = auth()->user()->companies()->first();

        try {
            $this->staffService->inviteStaffMember($compannyUser, $validated);
            return redirect()->back()->with('success', 'Staff member invited successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'You are not part of any company.');
        }

    }
}
