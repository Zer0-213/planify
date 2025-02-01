"use client";

import FormModal from "@/src/components/ui/modals/formModal";
import CustomButton from "@/src/components/ui/customButton";
import FormLabel from "@/src/components/ui/texts/formLabel";
import AuthLink from "@/src/app/(auth)/login/authLink";
import {useActionState} from "react";
import {signUpAction} from "@/src/actions/auth";
import ErrorText from "@/src/components/ui/texts/errorTexts";
import {AuthState} from "@/src/actions/auth/state/authState";

const SignupPage = () => {
    const initialState: AuthState = {
        error: null
    }

    const [formState, formAction, isPending] = useActionState(signUpAction, initialState)

    return (
        <FormModal header="Sign Up">
            <form action={formAction} className="mt-6">
                <div className="flex flex-row gap-2.5">
                    <FormLabel htmlFor="firstName">
                        First Name
                        <input
                            type="text"
                            id="firstName"
                            name="firstName"
                            className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Enter your name"
                            required
                        />
                    </FormLabel>
                    <FormLabel htmlFor="lastName">
                        Last Name
                        <input
                            type="text"
                            id="lastName"
                            name="lastName"
                            className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Enter your name"
                            required
                        />
                    </FormLabel>
                </div>
                <FormLabel htmlFor="email">
                    Email
                    <input
                        type="email"
                        id="email"
                        name="email"
                        className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                        placeholder="Enter your email"
                        required
                    />
                </FormLabel>
                <FormLabel htmlFor="password">
                    Password
                    <input
                        type="password"
                        id="password"
                        name="password"
                        className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                        placeholder="Enter your password"
                        required
                    />
                </FormLabel>
                <FormLabel htmlFor="confirmPassword">
                    Confirm Password
                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                        placeholder="Confirm your password"
                        required
                    />
                </FormLabel>
                <FormLabel htmlFor={"dateOfBirth"}>
                    Date of Birth
                    <input
                        type="date"
                        id="dateOfBirth"
                        name="dateOfBirth"
                        className="w-full px-3 py-2 mt-1 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                        required
                    />
                </FormLabel>
                <div className="flex flex-col w-full justify-center">
                    <CustomButton type="submit" isLoading={isPending} text="Sign Up"/>
                </div>
            </form>
            <AuthLink href={'/login'}/>
            {formState.error && <ErrorText text={formState.error}/>}
        </FormModal>
    )
}

export default SignupPage;