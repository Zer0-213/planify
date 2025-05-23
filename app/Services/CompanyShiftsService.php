<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Shift;
use Carbon\Carbon;

class CompanyShiftsService
{
    public function getShiftsForWeek(Company $company, Carbon $startWeek, Carbon $endWeek): array
    {
        return $company->companyUsers()
            ->with([
                'user',
                'shifts' => function ($query) use ($startWeek, $endWeek) {
                    $query->whereBetween('starts_at', [
                        $startWeek->copy()->startOfDay(),
                        $endWeek->copy()->endOfDay()
                    ]);
                }
            ])
            ->get()
            ->map(function ($companyUser) use ($startWeek) {
                $shifts = $companyUser->shifts->groupBy(function ($shift) {
                    return strtolower(Carbon::parse($shift->starts_at)->format('l'));
                });

                $shiftData = [];

                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                foreach ($weekdays as $index => $day) {
                    $date = $startWeek->copy()->startOfWeek()->addDays($index);
                    $shift = $shifts->get($day)?->first();
                    $shiftData[$day] = $this->formatShift($shift, $date);
                }

                return [
                    'name' => $companyUser->user->name,
                    'user_id' => $companyUser->user->id,
                    'shifts' => $shiftData,
                ];
            })
            ->toArray();
    }

    private function formatShift(?object $shift, Carbon $date): ?array
    {
        return [
            'id' => $shift?->id,
            'date' => $date->format('Y-m-d'),
            'starts_at' => $shift?->starts_at?->toIso8601String(),
            'ends_at' => $shift?->ends_at?->toIso8601String(),
        ];
    }

    public function upsertShifts(array $shifts, Shift $shiftModel, Company $company): void
    {
        $timezone = config('app.timezone');
        $upserts = [];

        foreach ($shifts as $shift) {
            $companyUser = $company->companyUsers()->where('user_id', $shift['user_id'])->first();

            foreach ($shift['shifts'] as $shiftData) {
                if (empty($shiftData['starts_at'])) {
                    continue;
                }

                $upserts[] = [
                    'id' => $shiftData['id'] ?? null,
                    'company_user_id' => $companyUser->id,
                    'starts_at' => Carbon::parse($shiftData['starts_at'], $timezone)->toDateTimeString(),
                    'ends_at' => $shiftData['ends_at']
                        ? Carbon::parse($shiftData['ends_at'], $timezone)->toDateTimeString()
                        : null,
                ];
            }
        }

        $shiftModel->upsert($upserts, ['id'], ['starts_at', 'ends_at']);
    }
}
