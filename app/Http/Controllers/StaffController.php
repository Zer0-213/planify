<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Services\StaffService;
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


}
