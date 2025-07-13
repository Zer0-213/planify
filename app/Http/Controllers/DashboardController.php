<?php

namespace App\Http\Controllers;

use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Validation\UnauthorizedException;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly ShiftService $shiftService)
    {

    }

    public function index(): Response
    {
        $companyUser = auth()->user()->companyUsers()->first();
        if (!$companyUser) {
            throw new UnauthorizedException();
        }

        $todayShift = $this->shiftService->getShifts($companyUser->company, Carbon::today()->startOfDay(), Carbon::today()->endOfDay(), $companyUser->id);
        $upcomingShifts = $this->shiftService->getShifts($companyUser->company, Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), $companyUser->id);

        return Inertia::render("dashboard/Index", [
                'todayShift' => $todayShift[0],
                'upcomingShifts' => $upcomingShifts[0],
            ]
        );
    }
}
