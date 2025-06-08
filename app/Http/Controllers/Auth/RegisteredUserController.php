<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyInvite;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $body = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'invite_id' => 'nullable|integer',
            'invite_token' => 'nullable|string',
        ]);

        $user = (new User)->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($body['invite_id'] && $body['invite_token']) {
            $invite = CompanyInvite::query()->where('id', $request->invite_id)
                ->where('expires_at', '>', now())
                ->first();

            if (!$invite) {
                return back()->withErrors([
                    'error' => 'The invitation has expired or does not exist.',
                ])->withInput();
            }

            if (!Hash::check($body['invite_token'], $invite->token)) {
                return back()->withErrors([
                    'error' => 'The invitation link is invalid.',
                ])->withInput();
            }

            $companyUser = $user->companyUsers()->create([
                'company_id' => $invite->company_id,
                'wage' => $invite->wage,
            ]);

            $companyUser->roles()->attach($invite->role_id);

            $invite->phone_number && $user->update([
                'phone_number' => $invite->phone_number,
            ]);

            session()?->flash('company_id', $invite->company_id);
            session()?->flash('just_accepted_invite');

            $invite->delete();

        }

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }

    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }
}
