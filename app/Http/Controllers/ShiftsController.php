<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftsRequest;
use App\Models\Company;
use App\Models\Shift;
use App\Models\User;
use App\Services\CompanyShiftsService;
use Carbon\CarbonInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class ShiftsController extends Controller
{

    public function __construct(private readonly CompanyShiftsService $companyShiftsService)
    {
    }

    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Company $company */
        $company = $user->companies()->first();

        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'))->startOfWeek(CarbonInterface::MONDAY)
            : now()->startOfWeek(CarbonInterface::MONDAY);

        $weekEnd = $weekStart->copy()->endOfWeek(CarbonInterface::SUNDAY);


        $shifts = $this->companyShiftsService->getShiftsForWeek($company, $weekStart, $weekEnd);

        return Inertia::render('shifts/ShiftsMain', [
            'shifts' => $shifts,
            'week' => $weekStart
        ]);
    }

    /**
     * Store or update the shifts for a given company.
     *
     * @param StoreShiftsRequest $request
     * @param Shift $shiftModel
     * @return RedirectResponse
     */
    public function store(StoreShiftsRequest $request, Shift $shiftModel): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Company $company */
        $company = $user->companies()->first();

        // Validate the request
        $validated = $request->validated();

        // Upsert the shifts
        $this->companyShiftsService->upsertShifts($validated['shifts'], $shiftModel, $company);

        // Get the week parameter for redirecting back to the same week view
        $week = $request->input('week', now()->format('Y-m-d'));

        return redirect()->route('shifts.index', ['week' => $week])
            ->with('success', 'Shifts updated successfully');
    }

}

