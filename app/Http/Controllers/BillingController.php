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
            ->returnTo(route('billing.processing'));

        return view('billing', [
            'checkout' => $checkout,
        ]);
    }

    /**
     * Handle the return from the Paddle checkout-show processing page.
     */
    public function processing(Request $request)
    {
        $user = $request->user();

        // Check if the subscription is already active (in case the webhook was superfast)
        $subscription = $user->subscription();
        if ($subscription && $subscription->active()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('billing/Processing', [
            'user' => $user->load('subscriptions'),
            'checkInterval' => 3000, // Check every 3 seconds
            'maxWaitTime' => 60000,  // Give up after 1 minute
        ]);
    }

    /**
     * API endpoint to check subscription status.
     */
    public function checkSubscription(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription();

        return response()->json([
            'subscribed' => $subscription && $subscription->active(),
            'subscription' => $subscription ? [
                'status' => $subscription->status,
                'active' => $subscription->active(),
            ] : null,
        ]);
    }
}
