import SignUpForm from "~/routes/signup._index/components/signUpForm";
import {registerUser} from "~/services/api/auth";
import {ConflictException} from "~/exceptions/conflictException";
import {redirect} from "react-router";
import {authToken} from "~/utils/cookies";

export const action = async ({request}: { request: Request }) => {
    const formData = await request.formData();
    const firstName = formData.get("firstName");
    const lastName = formData.get("lastName");
    const email = formData.get("email");
    const password = formData.get("password");
    const dateOfBirth = formData.get("dateOfBirth");

    if (!firstName || !lastName || !email || !password || !dateOfBirth) {
        return {
            error: "All fields are required.",
        }
    }

    const dob = new Date(dateOfBirth.toString());
    const age = new Date().getFullYear() - dob.getFullYear() -
        (new Date() < new Date(dob.setFullYear(new Date().getFullYear())) ? 1 : 0);

    if (age < 18) {
        return {
            error: "You must be at least 18 years old to sign up.",
        }
    }

    try {
        const data = await registerUser({
            firstName: firstName.toString(),
            lastName: lastName.toString(),
            email: email.toString(),
            password: password.toString(),
            dateOfBirth: dob,
        });

        return redirect("/signup/company", {
            headers: {
                "Set-Cookie": await authToken.serialize(data.token, {
                    expires: new Date(data.expiresAt)
                })
            }
        })
    } catch (e) {
       if (e instanceof ConflictException || e instanceof ServerException) {
           return {
               error: e.message
           }
       }else {
           console.error(e);
          return {
              error: "Something went wrong. Please try again later."
          }
       }
    }

}


const SignUpPage = () => {
    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100 p-4">
            <div className="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
                <h1 className="text-2xl font-bold text-center mb-6">Sign Up</h1>
                <SignUpForm/>
            </div>
        </div>
    );
}

export default SignUpPage