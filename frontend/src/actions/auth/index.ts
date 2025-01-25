"use server"

import {LoginDTO, LoginResponseDTO} from "@/src/actions/auth/auth/dtos/loginDTOs";
import {redirect} from "next/navigation";
import {cookies} from "next/headers";
import {AuthState} from "@/src/actions/auth/auth/state/authState";

export async function login(currentState: AuthState, formData: FormData) {
    const email = formData.get('email') as string;
    const password = formData.get('password') as string;

    try {
        console.log(`${process.env.SERVER_URL}/api/auth/login`)
        const response = await fetch(`${process.env.SERVER_URL}/api/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({email, password} as LoginDTO),
        });

        if (!response.ok) {
            if (response.status === 401) {
                return {error: 'Invalid email or password'};
            } else {
                return {error: 'An unexpected error occurred. Please try again later.'};
            }
        }

        const data = (await response.json()) as LoginResponseDTO;

        (await cookies()).set('token', data.token, {
            expires: new Date(data.expiresAt),
            secure: process.env.NODE_ENV === 'production',
            sameSite: 'strict',
            httpOnly: true,
        })

        return redirect('/dashboard',);
    } catch (e) {
        console.error(e);
        return {error: 'An unexpected error occurred. Please try again later.'};
    }

}