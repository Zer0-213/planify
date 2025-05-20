<?php

namespace App\Http\Controllers;

use App\Actions\GetCompanyShiftsForWeekAction;
use App\Models\Company;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class ShiftsController extends Controller
{
    public function index(Request $request, GetCompanyShiftsForWeekAction $getCompanyShiftsForWeek): Response
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Company $company */
        $company = $user->companies()->first();

        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'))->startOfWeek(CarbonInterface::MONDAY)
            : now()->startOfWeek(CarbonInterface::MONDAY);

        $weekEnd = $weekStart->copy()->endOfWeek(CarbonInterface::SUNDAY);


        $shifts = $getCompanyShiftsForWeek->execute($company, $weekStart, $weekEnd);

        return Inertia::render('shifts/ShiftsMain', [
            'shifts' => $shifts,
            'week' => $weekStart
        ]);
    }

}

