<?php

namespace App\Actions\Company;

use App\Models\Company;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;

class StoreCompanyAction
{
    public function execute(array $validated): RedirectResponse
    {
        $company = Company::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone'],
            'type' => $validated['type'],
            'owner_id' => auth()->id(),
        ]);

        // Attach the current user to the company
        $company->users()->attach(auth()->id());

        // Get the newly created company user record
        $companyUser = $company->companyUsers()->where('user_id', auth()->id())->first();

        // Get all available permissions and attach them
        $allPermissions = Permission::all();
        $companyUser->permissions()->attach($allPermissions->pluck('id')->toArray());

        return redirect()->route("dashboard");
    }
}
