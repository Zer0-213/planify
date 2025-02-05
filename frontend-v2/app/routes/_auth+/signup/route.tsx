import FormModal from "~/components/ui/modals/formModal";
import FormLabel from "~/components/ui/texts/formLabel";
import CustomButton from "~/components/ui/customButton";
import AuthLink from "~/routes/_auth+/login/authLink";
import ErrorText from "~/components/ui/texts/errorTexts";
import {cookieStore} from "~/utils/cookies";
import {redirect} from "react-router";

export async function loader({request}: { request: Request }) {
    const cookie = request.headers.get("Cookie");
    if (cookie) {
        const sessionId = await cookieStore.parse(cookie);
        if (sessionId) {
            return redirect("/dashboard");
        }
    }
}

const SignupPage = () => {
    return (
        <FormModal header="Sign Up">
            <form className="mt-6">
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
                    <CustomButton type="submit" isLoading={false} text="Sign Up"/>
                </div>
            </form>
            <AuthLink href={'/login'}/>
            {<ErrorText text={"test"}/>}
        </FormModal>
    )
}

export default SignupPage;