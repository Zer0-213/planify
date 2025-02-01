import {RegisterDTO} from "@/src/actions/auth/dtos/registerDTOs";
import {ExistsException} from "@/src/utils/exceptions/existsException";

export async function registerService(data: RegisterDTO): Promise<void> {
    const response = await fetch(`${process.env.SERVER_URL}/api/auth/register`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
        credentials: 'include',
    });

    if (!response.ok) {
        console.log(response)
        if (response.status === 409) {
            throw new ExistsException()
        } else {
            throw new Error();
        }
    }

}