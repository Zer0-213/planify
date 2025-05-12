<?php

namespace App\Http\Controllers;

use App\Actions\GetCompanyShiftsForWeek;
use App\Models\Company;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ShiftsController extends Controller
{
    public function index(GetCompanyShiftsForWeek $getCompanyShiftsForWeek): Response
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Company $company */
        $company = $user->companies()->first();
        
        $shifts = $getCompanyShiftsForWeek->execute($company);

        return Inertia::render('shifts/Index', ['shifts' => $shifts]);
    }

}

