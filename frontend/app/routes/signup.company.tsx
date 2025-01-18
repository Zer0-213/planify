import {createCompany} from "~/services/api/company";
import {redirect, replace, useNavigation} from "react-router";
import CreateCompany from "~/components/pages/CreateCompany";
import {getUserData} from "~/services/api/user";
import {authToken, checkAuthCookie} from "~/utils/cookies";

const loader = async ({request}: { request: Request }) => {
    try {
        if (!await checkAuthCookie(request)) {
            return redirect("/login");
        }

        const user = await getUserData();
        if (!user.success) {
            if (user?.error?.code === 401) {
                return redirect("/login", {
                    headers: {
                        "Set-Cookie": await authToken.serialize("", {
                            maxAge: 0,
                        })
                    }
                });
            }
            throw new Error(user.error?.message || "Unknown error");
        }

        if (user?.data?.companyId) {
            return redirect("/dashboard");
        }

        return {...user?.data}
    } catch (e) {
        console.error("Loader error:", e);
        return redirect("/error");
    }
}

export const action = async ({request}: { request: Request }) => {
    const formData = await request.formData();
    const companyName = formData.get("companyName");
    const companyAddress = formData.get("companyAddress") as string;
    const phoneNumber = formData.get("companyPhone") as string;
    const companyType = formData.get("companyType") as string;

    try {
        const response = await createCompany({
            companyName: companyName as string,
            companyAddress: companyAddress as string,
            companyPhone: phoneNumber as string,
            companyType: Number(companyType),
        });

        if (!response.success) {
            if (response?.error?.code === 401) {
                return redirect("/login");
            }

            return {
                error: response.error?.message,
            };
        }

        return replace("/dashboard");

    } catch (e) {
        console.error(e);
        return {status: 500, error: "Internal Server Error"};
    }
}

const Company = () => {
    const navigation = useNavigation();

    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100">
        <CreateCompany navigation={navigation}/>
        </div>
    )
}

export default Company;