<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftService
{
    public function getShifts(Company $company, Carbon $startDay, Carbon $endDay): array
    {
        return $company->companyUsers()
            ->with([
                'user',
                'shifts' => function ($query) use ($startDay, $endDay) {
                    $query->whereBetween('shift_date', [
                        $startDay->copy()->startOfDay(),
                        $endDay->copy()->endOfDay()
                    ]);
                }
            ])
            ->get()
            ->map(function ($companyUser) {
                $shifts = $companyUser->shifts->map(function ($shift) {
                    return [
                        'id' => $shift?->id,
                        'starts_at' => $shift->starts_at?->toIso8601String(),
                        'ends_at' => $shift->ends_at?->toIso8601String(),
                        'shift_date' => $shift?->shift_date?->toIso8601String(),
                        'duration_in_minutes' => $shift->starts_at && $shift->ends_at
                            ? $shift->ends_at->diffInMinutes($shift->starts_at)
                            : 0,
                    ];
                });

                $totalMinutes = $shifts->sum('duration_in_minutes');

                return [
                    'user_id' => $companyUser->user->id,
                    'name' => $companyUser->user->name,
                    'total_minutes' => $totalMinutes,
                    'total_hours' => round($totalMinutes / 60, 2),
                    'shifts' => $shifts,
                ];
            })
            ->toArray();
    }

    public function getShiftByCompanyUser(CompanyUser $companyUser, Carbon $startDay, Carbon $endDay)
    {
        return $companyUser->shifts()->where('company_user_id', $companyUser->id)
            ->whereBetween('shift_date', [
                $startDay->copy()->startOfDay(),
                $endDay->copy()->endOfDay()
            ])
            ->first();
    }

    public function upsertShifts(array $shifts, Shift $shiftModel, Company $company): void
    {
        $timezone = config('app.timezone');
        $upserts = [];

        foreach ($shifts as $shift) {
            $companyUser = $company->companyUsers()->where('user_id', $shift['user_id'])->first();

            foreach ($shift['shifts'] as $shiftData) {

                $startDate = $shiftData['starts_at'] ? Carbon::parse($shiftData['starts_at'], $timezone) : null;
                $endDate = $shiftData['ends_at'] ? Carbon::parse($shiftData['ends_at'], $timezone)->toDateTimeString() : null;

                $upserts[] = [
                    'id' => $shiftData['id'] ?? null,
                    'company_user_id' => $companyUser->id,
                    'shift_date' => Carbon::parse($shiftData['shift_date'], $timezone)->toDate(),
                    'starts_at' => $startDate,
                    'ends_at' => $endDate
                ];
            }
        }

        $shiftModel->upsert($upserts, ['id'], ['starts_at', 'ends_at']);
    }
}
