<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BillingController extends Controller
{
    /**
     * Show the billing notice page (for inactive subscriptions).
     */
    public function notice(Request $request)
    {
        $user = $request->user();

        $company = $user->companyUsers()
            ->where('is_default', true)
            ->first()
            ->company;

        $isOwner = $company && $company->owner_id === $user->id;

        return Inertia::render('billing/Notice', [
            'isOwner' => $isOwner,
        ]);
    }

    /**
     * Redirect the company owner to the Paddle billing portal.
     */
    public function portal(Request $request)
    {
        $user = $request->user();

        $company = $user->companyUsers()
            ->where('is_default', true)
            ->first()
            ->company;

        if (!$company || $company->owner_id !== $user->id) {
            abort(403, 'Unauthorized access to billing portal.');
        }

        $subscription = $user->subscription();

        if ($subscription && $subscription->active()) {
            $portalUrl = $user->paddle_customer_portal_url ?? null;
            return $portalUrl
                ? redirect()->away($portalUrl)
                : redirect()->route('dashboard');
        }

        $checkout = $user->checkout('pri_01k2524wtmcmerd1pw691g0cpr')
            ->returnTo(route('dashboard'));


        return view('billing', [
            'checkout' => $checkout,
        ]);

    }

}
