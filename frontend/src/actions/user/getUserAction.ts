import {cookies} from "next/headers";
import {GetUserDTO} from "@/src/dtos/getUserDTO";
import {redirect} from "next/navigation";
import {ErrorDTO} from "@/src/utils/dtos/errorDTO";

export const getUserAction = async (): Promise<GetUserDTO | ErrorDTO> => {
    const cookieStore = await cookies();
    const sessionId = cookieStore.get('SessionId');

    const response = await fetch(`${process.env.SERVER_URL}/api/user/get-user`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                "Authorization": sessionId?.value || ''
            },
            credentials: 'include',
        }
    )

    if (!response.ok) {
        if (response.status === 401) {
            cookieStore.delete('SessionId');
            return redirect('/login');
        }
        return {
            code: response.status,
            message: 'Please try again',
            description: 'An error occurred while fetching user data'
        } as ErrorDTO;
    }

    return await response.json() as GetUserDTO;
}