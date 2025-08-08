<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Company\StoreCompanyAction;
use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    public function index(): Response
    {

        $companies = Company::all();

        return Inertia::render('settings/Companies', [
            'companies' => $companies
        ]);
    }

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

    public function destroy(Company $company): RedirectResponse
    {
        $user = auth()->user();
        $companyUser = $user->companyUsers()->where('company_id', $company->id)->first();

        if (!$companyUser->hasPermission(PermissionEnum::DELETE_COMPANY)) {
            throw new AuthorizationException('You do not have permission to delete this company.');
        }

        // Delete the company
        $company->delete();

        return redirect()->route('settings.companies.index')
            ->with('success', 'Company deleted successfully');
    }

    public function disable(Company $company): RedirectResponse
    {
        $companyUser = auth()->user()->companyUsers()->where('company_id', $company->id)->first();
        if (!$companyUser->hasPermission(PermissionEnum::DISABLE_COMPANY)) {
            throw new AuthorizationException('You do not have permission to disable this company.');
        }

        $company->update(['is_active' => false]);


        return redirect()->route('settings.companies.index')
            ->with('success', 'Company disabled successfully');
    }
}
