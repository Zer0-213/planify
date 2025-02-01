'use client'
import {loginAction} from "@/src/actions/auth";
import {AuthState} from "@/src/actions/auth/state/authState";
import {useActionState} from "react";
import CustomButton from "../../../components/ui/customButton";
import ErrorText from "@/src/components/ui/texts/errorTexts";
import FormLabel from "@/src/components/ui/texts/formLabel";
import FormModal from "@/src/components/ui/modals/formModal";
import AuthLink from "@/src/app/(auth)/login/authLink";


const LoginPage = () => {
    const initialState: AuthState = {
        error: null
    }
    const [formState, formAction, isPending] = useActionState(loginAction, initialState)
    return (
        <FormModal header="Login">
            <form action={formAction} className="mt-6">
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
                <div className="flex flex-col w-full justify-center gap-2">
                    <CustomButton type="submit" isLoading={isPending} text="Log In"/>
                    {formState.error && <ErrorText text={formState.error}/>}
                </div>
            </form>
            <AuthLink href={'/signup'}/>
        </FormModal>
    );
}
export default LoginPage;