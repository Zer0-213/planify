import {redirect, useActionData} from "react-router";
import LoginForm from "~/routes/login/components/loginForm";
import {authToken, checkAuthCookie} from "~/utils/cookies";
import * as process from "node:process";
import {getUserData} from "~/services/api/user";

export const loader = async ({request}: { request: Request }) => {
    if (await checkAuthCookie(request)) {
        const user = await getUserData();
        if (!user.data) {
            return
        } else if (user.data.companyId) {
            return redirect("/dashboard");
        } else if (!user.data.companyId) {
            return redirect("/create-company");
        } else {
            return;
        }
    }
}

export const action = async ({request}: { request: Request }) => {
    const formData = await request.formData();
    const email = formData.get("email");
    const password = formData.get("password");

    const response = await fetch(process.env.SERVER_URL + "/auth/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email,
            password,
        }),
    });

    const responseJson = (await response.json()) as ResponseDTO<LoginActionData>;

    if (!responseJson.success) {
        return {
            error: responseJson.error?.description,
        };
    }

    const data = responseJson.data as LoginActionData;


    return redirect("/", {
        headers: {
            "Set-Cookie": await authToken.serialize(data.token, {
                expires: new Date(data.expiresAt),
            }),
        },
    });
};

export default function LoginPage() {
    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100 p-4">
            <div className="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
                <h1 className="text-2xl font-bold text-center mb-6">Login</h1>
                <LoginForm/>
            </div>
        </div>
    );
}
