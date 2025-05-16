<?php

namespace App\Actions;

use App\Models\Company;
use Carbon\Carbon;

class GetCompanyShiftsForWeek
{
    public function execute(Company $company, Carbon $startWeek, Carbon $endWeek): array
    {

        return $company->companyUsers()->with(['user', 'shifts' => function ($query) use ($startWeek, $endWeek) {
            $query->whereBetween('starts_at', [
                $startWeek->startOfDay(),
                $endWeek->endOfDay()
            ]);
        }])->get()->map(function ($companyUser) {
            $shifts = $companyUser->shifts->groupBy(function ($shift) {
                return strtolower(Carbon::parse($shift->starts_at)->format('l'));
            });

            return [
                'name' => $companyUser->user->name,
                'monday' => $this->formatShift($shifts->get('monday')?->first()),
                'tuesday' => $this->formatShift($shifts->get('tuesday')?->first()),
                'wednesday' => $this->formatShift($shifts->get('wednesday')?->first()),
                'thursday' => $this->formatShift($shifts->get('thursday')?->first()),
                'friday' => $this->formatShift($shifts->get('friday')?->first()),
                'saturday' => $this->formatShift($shifts->get('saturday')?->first()),
                'sunday' => $this->formatShift($shifts->get('sunday')?->first()),
            ];
        })->toArray();

    }

    private function formatShift(?object $shift): ?string
    {
        if (!$shift) return null;

        $start = Carbon::parse($shift->starts_at);
        $end = Carbon::parse($shift->ends_at);

        return $start->day !== $end->day
            ? $start->format('H:i') . '-'
            : $start->format('H:i') . '-' . $end->format('H:i');
    }
}

