import {LoginDTO, LoginResponseDTO} from "@/src/actions/auth/dtos/loginDTOs";
import {UnAuthorisedException} from "@/src/utils/exceptions/unAuthorisedException";
import {cookies} from "next/headers";

export async function sendLoginRequest(loginDTO: LoginDTO): Promise<LoginResponseDTO> {
    const response = await fetch(`${process.env.SERVER_URL}/api/auth/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(loginDTO),
        credentials: 'include',
    });

    if (!response.ok) {
        if (response.status === 401) {
            throw new UnAuthorisedException();
        } else {
            throw new Error();
        }
    }

    const data = await response.json() as LoginResponseDTO;

    const cookieStore = await cookies();
    cookieStore.set("SessionId", data.token,{
        expires: new Date(data.expiresAt),
        httpOnly: true,
        sameSite: 'lax',
        secure: true,
        path: '/',
    })

    cookieStore.set("hasCompany", data?.companyId ? 'true' : 'false', {
        expires: new Date(data.expiresAt),
        httpOnly: true,
        sameSite: 'lax',
        secure: true,
        path: '/',
    })

    return data;
}