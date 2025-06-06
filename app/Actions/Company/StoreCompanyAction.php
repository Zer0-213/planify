<?php

namespace App\Actions\Company;

use App\Enums\RoleEnum;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        $companyUser->permissions()->attach(Permission::all()->pluck('id')->toArray());
        $companyUser->roles()->attach(Role::query()->where('name', RoleEnum::ADMIN)->first()->id);

        return redirect()->route("dashboard");
    }
}
