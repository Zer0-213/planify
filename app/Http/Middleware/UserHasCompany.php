<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHasCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $companyUser = $user->companyUsers()->first();

        if (!$companyUser) {
            return redirect()->route('company.index');
        }

        $company = Company::withTrashed()->find($companyUser->company_id);

        if (!$company) {
            return redirect()->route('company.index');
        }

        if ($company->trashed()) {
            return redirect()->route('company.deleted.notice');
        }

        return $next($request);
    }

}
