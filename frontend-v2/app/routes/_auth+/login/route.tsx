import CustomButton from "~/components/ui/customButton";
import ErrorText from "~/components/ui/texts/errorTexts";
import FormModal from "~/components/ui/modals/formModal";
import AuthLink from "~/routes/_auth+/login/authLink";
import {cookieStore} from "~/utils/cookies";
import {redirect, useActionData, useNavigation} from "react-router";
import * as process from "node:process";
import type {LoginResponseDTO} from "~/dtos/auth/loginDtos";
import CustomInput from "~/components/ui/inputs/customInput";

export async function loader({request}: { request: Request }) {
    const cookie = request.headers.get("Cookie");
    if (cookie) {
        const sessionId = await cookieStore.parse(cookie);
        if (sessionId) {
            return redirect("/dashboard");
        }
    }
}

export async function action({request}: { request: Request }) {
    const formData = await request.formData();
    const email = formData.get("email") as string;
    const password = formData.get("password") as string;

    try {
        const response = await fetch(`${process.env.SERVER_URL}/api/auth/login`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({email, password}),
        });

        if (!response.ok) {
            if (response.status === 401) {
                return {
                    error: "Invalid email or password",
                };
            } else {
                console.log(response);
                return {
                    error: "An error occurred while logging in",
                }
            }
        }
        const data = await response.json() as LoginResponseDTO;

        return redirect(data.companyId ? "/dashboard" : "create-company", {
            headers: {
                "Set-Cookie": await cookieStore.serialize(data.token, {
                    httpOnly: true,
                    secure: true,
                    sameSite: "lax",
                    expires: new Date(data.expiresAt),
                })
            }
        });
    } catch (e) {
        console.error(e);
        return {
            error: "An error occurred while logging in"
        }
    }


}

const LoginPage = () => {
    const navigate = useNavigation();
    const error = useActionData<typeof action>()
    return (
        <FormModal header="Login">
            <CustomInput htmlFor="email"
                         name="email"
                         label="Email"
                         type="email"
                         placeholder="Enter your email"
                         required/>

            <CustomInput htmlFor="password"
                         name="password"
                         label="Password"
                         type="password"
                         placeholder="Enter your password"
                         required/>

            <div className="flex flex-col w-full justify-center gap-2">
                <CustomButton type="submit" isLoading={navigate.state === "loading"} text="Log In"/>
                {error?.error && <ErrorText text={error.error}/>}
            </div>
            <AuthLink href={'/signup'}/>
        </FormModal>
    );
}
export default LoginPage;