<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $company = $user->companyUsers()->where('is_default', true)->first()?->company;

        if (!$company) {
            abort(403, 'No company found.');
        }

        $owner = User::find($company->owner_id);

        if (!$owner->subscribed()) {
            return redirect()->route('billing.notice');
        }

        return $next($request);
    }
}
