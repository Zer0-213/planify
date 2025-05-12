<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'string', 'max:255', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'type' => 'required|string|max:255',
        ]);
        
        $company = Company::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone'],
            'type' => $validated['type'],
            'owner_id' => auth()->id(), // Owner of the company
        ]);

        // Attach the current user to the company (add to the pivot table)
        $company->users()->attach(auth()->id());

        return redirect()->route("dashboard");
    }


    public function create(): Response
    {
        return Inertia::render('company/Create');
    }
}
