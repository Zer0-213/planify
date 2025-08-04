<?php

use App\Models\CompanyUser;

function getCurrentCompanyUser(Illuminate\Http\Request $request): CompanyUser
{
    return $request->user()->companyUsers()->first();
}
