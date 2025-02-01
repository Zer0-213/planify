"use server"

import {AuthState} from "@/src/actions/auth/state/authState";
import {sendLoginRequest} from "@/src/actions/auth/services/loginService";
import {UnAuthorisedException} from "@/src/utils/exceptions/unAuthorisedException";
import {ExistsException} from "@/src/utils/exceptions/existsException";
import {registerService} from "@/src/actions/auth/services/registerService";
import {redirect} from "next/navigation";
import {createCompanyRequest} from "@/src/actions/auth/services/createCompanyService";

export async function loginAction(currentState: AuthState, formData: FormData) {
    const email = formData.get('email') as string;
    const password = formData.get('password') as string;
    let redirectPath: string;

    try {
        const data = await sendLoginRequest({email, password});
        
        redirectPath = data?.companyId ? '/dashboard' : '/create-company';

    } catch (e) {
        if (e instanceof UnAuthorisedException) {
            return {
                error: 'Invalid email or password',
            };
        }

        console.log(e);
        return {
            error: 'An error occurred. Please try again later',
        };
    }
    
    return redirect(redirectPath);
}


export async function signUpAction(currentState: AuthState, formData: FormData) {
    const firstName = formData.get('firstName') as string;
    const lastName = formData.get('lastName') as string;
    const email = formData.get('email') as string;
    const password = formData.get('password') as string;
    const confirmPassword = formData.get('confirmPassword') as string;
    const dateOfBirth = formData.get('dateOfBirth') as string;

    if (password !== confirmPassword) {
        return {error: 'Passwords do not match'};
    }

    if (new Date(dateOfBirth) > new Date()) {
        return {error: 'Date of birth cannot be in the future'};
    }

    if (new Date().getFullYear() - new Date(dateOfBirth).getFullYear() < 18) {
        return {error: 'You must be at least 18 years old to register'};
    }

    try {
        await registerService({firstName, lastName, email, password, dateOfBirth: new Date(dateOfBirth).toISOString()});
    } catch (e) {
        if (e instanceof ExistsException) {
            return {
                error: 'Email already exists',
            };
        }

        console.log(e);
        return {
            error: 'An error occurred. Please try again later',
        }
    }

    return redirect('/create-company');
}

export async function submitCompanyAction(currentState: AuthState, formData: FormData) {
    const companyName = formData.get('companyName') as string;
    const companyAddress = formData.get('companyAddress') as string;
    const companyNumber = formData.get('companyNumber') as string;
    const companyType = formData.get('companyType') as string;

   const response = await createCompanyRequest({companyName, companyAddress,  companyNumber, companyType: parseInt(companyType)});
   if (response.ok) {
       return redirect('/dashboard');
   }else {
       switch (response.status) {
           case 401:
               return redirect('/login');
           case 404:
               console.log("wrong endpoint");
               break
           case 400:
               console.log(await response.json());
               break
           default:
               console.log(response);
       }
       return {error: "An error occurred while creating the company"}
   }
}
