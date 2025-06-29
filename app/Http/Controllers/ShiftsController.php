<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftsRequest;
use App\Models\Company;
use App\Models\Shift;
use App\Models\User;
use App\Services\ShiftService;
use Carbon\CarbonInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class ShiftsController extends Controller
{
    public function __construct(private readonly ShiftService $companyShiftsService)
    {
    }

    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Company $company */
        $company = $user->companies()->first();

        $timezone = config('app.timezone');

        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'), $timezone)->startOfWeek(CarbonInterface::MONDAY)
            : now()->setTimezone($timezone)->startOfWeek(CarbonInterface::MONDAY);

        $weekEnd = $weekStart->copy()->endOfWeek(CarbonInterface::SUNDAY);

        $shifts = $this->companyShiftsService->getShifts($company, $weekStart, $weekEnd);

        return Inertia::render('shifts/ShiftsMain', [
            'shifts' => $shifts,
            'week' => $weekStart->format('Y-m-d'),
        ]);
    }

    public function store(StoreShiftsRequest $request, Shift $shiftModel): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Company $company */
        $company = $user->companies()->first();

        $request->authorize('create', [$shiftModel, $company->id]);

        $validated = $request->validated();

        $this->companyShiftsService->upsertShifts($validated['shifts'], $shiftModel, $company);

        $week = $request->input('week', now()->format('Y-m-d'));

        return redirect()->route('shifts.index', ['week' => $week])
            ->with('success', 'Shifts updated successfully');
    }
}
