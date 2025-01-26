"use server"

import {redirect} from "next/navigation";
import {cookies} from "next/headers";
import {AuthState} from "@/src/actions/auth/state/authState";
import {sendLoginRequest} from "@/src/actions/auth/services/loginService";
import {UnAuthorisedException} from "@/src/utils/exceptions/unAuthorisedException";
import {ExistsException} from "@/src/utils/exceptions/existsException";
import {registerService} from "@/src/actions/auth/services/registerService";

export async function loginAction(currentState: AuthState, formData: FormData) {
    const email = formData.get('email') as string;
    const password = formData.get('password') as string;

    try {
        const data = await sendLoginRequest({email, password});

        (await cookies()).set('session_token', data.token, {
            expires: new Date(data.expiresAt),
            secure: process.env.NODE_ENV === 'production',
            sameSite: 'strict',
            httpOnly: true,
        })

        return redirect('/dashboard',);
    } catch (e) {
        if (e instanceof UnAuthorisedException) {
            return {
                error: 'Invalid email or password',
            };
        }

        return {
            error: 'An error occurred. Please try again later',
        };
    }
}


export async function signUpAction(currentState: AuthState, formData: FormData) {
    const name = formData.get('name') as string;
    const email = formData.get('email') as string;
    const password = formData.get('password') as string;
    const confirmPassword = formData.get('confirmPassword') as string;
    const dateOfBirth = formData.get('dateOfBirth') as string;

    if (password !== confirmPassword) {
        return {error: 'Passwords do not match'};
    }

    if (new Date(dateOfBirth) > new Date()) {
        return {error: 'Date of birth cannot be in the future'};
    }

    if (new Date().getFullYear() - new Date(dateOfBirth).getFullYear() < 18) {
        return {error: 'You must be at least 18 years old to register'};
    }

    try {
        const data = await registerService({name, email, password, dateOfBirth});

        (await cookies()).set('session_token', data.token, {
            expires: new Date(data.expiresAt),
            secure: process.env.NODE_ENV === 'production',
            sameSite: 'strict',
            httpOnly: true,
        });

        return redirect('/create-company');

    } catch (e) {


        if (e instanceof ExistsException) {
            return {
                error: 'Email already exists',
            };
        }

        return {
            error: 'An error occurred. Please try again later',
        }

    }


}
