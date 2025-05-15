<?php

namespace App\Http\Controllers;

use App\Actions\Company\StoreCompanyAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    public function store(Request $request, StoreCompanyAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'string', 'max:255', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'type' => 'required|string|max:255',
        ]);

        return $action->execute($validated);
    }


    public function create(): Response
    {
        return Inertia::render('company/Create');
    }
}
