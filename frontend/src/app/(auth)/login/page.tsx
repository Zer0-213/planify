//import Form from "next/form";

'use client'

import Link from "next/link";
import {login} from "@/src/actions/auth";
import {AuthState} from "@/src/actions/auth/auth/state/authState";
import {useActionState} from "react";
import {useFormStatus} from "react-dom";


const LoginPage = () => {
    const initialState: AuthState = {
        error: null
    }
    const [formState, formAction] = useActionState(login, initialState)
    const {pending} = useFormStatus()
    return (
        <div className="flex items-center justify-center h-screen bg-gray-100">
            <div className="w-full max-w-sm p-6 bg-white rounded-2xl shadow-md">
                <h1 className="text-2xl font-semibold text-center text-gray-800">Login</h1>
                <form action={formAction} className="mt-6">
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-6">
                        Email
                        <input
                            type="email"
                            id="email"
                            name="email"
                            className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Enter your email"
                            required
                        />
                    </label>
                    <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-6">
                        Password
                        <input
                            type="password"
                            id="password"
                            name="password"
                            className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Enter your password"
                            required
                        />
                    </label>
                    <button
                        type="submit"
                        className="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 mb-4"
                    >
                        Log In
                    </button>
                    {formState.error && <p className="text-red-600">{formState.error}</p>}
                </form>
                <p className="mt-4 text-sm text-center text-gray-600">
                    Don&apos;t have an account?{' '}
                    <Link href="/register" className="text-blue-600 hover:underline">
                        Sign up
                    </Link>
                </p>
            </div>
        </div>
    );
}
export default LoginPage;