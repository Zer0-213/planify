<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHasCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->companies()->exists()) {
            return redirect()->route('company.create');
        }

        return $next($request);
    }
}
