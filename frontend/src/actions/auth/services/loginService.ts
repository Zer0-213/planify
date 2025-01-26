import {LoginDTO, LoginResponseDTO} from "@/src/actions/auth/dtos/loginDTOs";
import {UnAuthorisedException} from "@/src/utils/exceptions/unAuthorisedException";

export async function sendLoginRequest(loginDTO: LoginDTO): Promise<LoginResponseDTO> {
    const response = await fetch(`${process.env.SERVER_URL}/api/auth/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(loginDTO),
    });

    if (!response.ok) {
        if (response.status === 401) {
            throw new UnAuthorisedException();
        } else {
            throw new Error();
        }
    }

    return await response.json() as Promise<LoginResponseDTO>;

}