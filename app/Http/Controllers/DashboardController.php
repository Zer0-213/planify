<?php

namespace App\Http\Controllers;

use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Validation\UnauthorizedException;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private ShiftService $shiftService)
    {

    }

    public function index(): Response
    {
        $companyUser = auth()->user()->companyUsers()->first();
        if (!$companyUser) {
            throw new UnauthorizedException();
        }

        $todayShift = $this->shiftService->getShiftByCompanyUser($companyUser, Carbon::today(), Carbon::today());
        return Inertia::render("dashboard/Index", [
                'todayShift' => $todayShift
            ]
        );
    }
}
