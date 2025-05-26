<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    public function index(StaffService $staffService): Response
    {
        $staffMembers = $staffService->getStaffMembers(auth()->user()->companies()->first());

        return Inertia::render('staff/StaffMain', [
            'staffMembers' => $staffMembers,
        ]);
    }
}
