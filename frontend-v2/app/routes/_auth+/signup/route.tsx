import FormModal from "~/components/ui/modals/formModal";
import CustomButton from "~/components/ui/customButton";
import AuthLink from "~/routes/_auth+/login/authLink";
import ErrorText from "~/components/ui/texts/errorTexts";
import {cookieStore} from "~/utils/cookies";
import {redirect, useActionData} from "react-router";
import CustomInput from "~/components/ui/inputs/customInput";
import type {LoginResponseDTO} from "~/dtos/auth/loginDtos";

export async function loader({request}: { request: Request }) {
    const cookie = request.headers.get("Cookie");
    if (cookie) {
        const sessionId = await cookieStore.parse(cookie);
        if (sessionId) {
            return redirect("/dashboard");
        }
    }
}

export async function action({request}: { request: Request; }) {
    const formData = await request.formData();

    const firstName = formData.get("firstName") as string;
    const lastName = formData.get("lastName") as string;
    const email = formData.get("email") as string;
    const password = formData.get("password") as string;
    const confirmPassword = formData.get("confirmPassword") as string;
    const phoneNumber = formData.get("phoneNumber") as string;
    const dateOfBirth = formData.get("dateOfBirth") as string;

    if (password !== confirmPassword) {
        return {
            error: "Passwords do not match",
        }
    }

    if (password.length < 6) {
        return {
            error: "Password must be at least 6 characters long",
        }
    }

    const dateOfBirthDate = new Date(dateOfBirth);
    const currentDate = new Date();
    const age = currentDate.getFullYear() - dateOfBirthDate.getFullYear();
    if (age < 18) {
        return {
            error: "You must be at least 18 years old to sign up",
        }
    }

    let sessionResponse: LoginResponseDTO;

    try {
        const response = await fetch(`${process.env.SERVER_URL}/api/auth/register`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({firstName, lastName, email, password, dateOfBirth, phoneNumber}),
        })
        if (!response.ok) {
            switch (response.status) {
                case 400:
                    return {
                        error: "User with this email already exists",
                    }
                case 404:
                    console.log("path not found");
                    return {
                        error: "An error occurred. Please try again later",
                    }
                default:
                    console.log(await response.text());
                    return {
                        error: "An error occurred. Please try again later",
                    }
            }

        }

        sessionResponse = await response.json() as LoginResponseDTO;
    } catch (e) {
        return {
            error: "An error occurred. Please try again later",
        }
    }

    return redirect("/create-company", {
        headers: {
            "Set-Cookie": await cookieStore.serialize(sessionResponse.token, {
                httpOnly: true,
                secure: true,
                sameSite: "lax",
                expires: new Date(sessionResponse.expiresAt),
            })
        }
    });

}

const SignupPage = () => {
    const actionData = useActionData<typeof action>()
    return (
        <FormModal header="Sign Up">
            <div className="flex flex-row gap-2.5">
                <CustomInput htmlFor="firstName"
                             type="text"
                             name="firstName"
                             label="First Name"
                             placeholder="Enter your name"
                             required
                />
                <CustomInput label={"Last Name"} name={"lastName"} htmlFor="lastName" required/>
            </div>

            <CustomInput label="Email" htmlFor="email" name="email" type="email"
                         required/>

            <CustomInput label="Password" htmlFor="password" name="password" type="password"
                         required/>

            <CustomInput label="Confirm password" htmlFor="confirmPassword" name="confirmPassword" type="password"
                         required/>

            <CustomInput label="Phone Number" htmlFor="phoneNumber" name="phoneNumber"/>
            <CustomInput label={"Date Of Birth"} htmlFor="dateOfBirth" name="dateOfBirth" type="date" required/>
            <div className="flex flex-col w-full justify-center">
                <CustomButton type="submit" isLoading={false} text="Sign Up"/>
            </div>
            <AuthLink href={'/login'}/>
            {actionData?.error && <ErrorText text={actionData.error}/>}
        </FormModal>
    )
}

export default SignupPage;