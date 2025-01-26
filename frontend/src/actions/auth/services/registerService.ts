import {RegisterDTO, RegisterResponseDTO} from "@/src/actions/auth/dtos/registerDTOs";
import {ExistsException} from "@/src/utils/exceptions/existsException";

export async function registerService(data: RegisterDTO): Promise<RegisterResponseDTO> {
    const response = await fetch(`${process.env.SERVER_URL}/api/auth/register`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) {
        if (response.status === 409) {
            throw new ExistsException()
        } else {
            throw new Error();
        }
    }

    return await response.json() as Promise<RegisterResponseDTO>;
}