<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeOffRequest;
use App\Models\TimeOffRequest;
use Illuminate\Http\RedirectResponse;

class TimeOffRequestController extends Controller
{
    public function __construct()
    {

    }

    public function requestTimeOff(StoreTimeOffRequest $request): RedirectResponse
    {
        $companyUser = $request->user()->companyUsers()->first();

        $request->authorize('create', [TimeOffRequest::class, $companyUser]);

        $isFullDay = !$request->filled('start_time') && !$request->filled('end_time');

        TimeOffRequest::query()->create([
            'company_user_id' => $companyUser->id,
            'start_date' => $request->input('start_date'),
            'start_time' => $request->input('start_time'),
            'end_date' => $request->input('end_date'),
            'end_time' => $request->input('end_time'),
            'is_full_day' => $isFullDay,
            'reason' => $request->input('reason'),
        ]);

        return redirect()->back()->with('success', 'Time off request submitted successfully.');
    }
}
