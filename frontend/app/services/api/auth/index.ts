import {RegisterRequestDTO, RegisterResponseDTO} from "~/services/api/auth/dtos/registerDTOs";
import {ConflictException} from "~/exceptions/conflictException";

export const registerUser = async (registorDTO: RegisterRequestDTO): Promise<RegisterResponseDTO> => {

    const response = await fetch(process.env.SERVER_URL + "/auth/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(registorDTO),
    });

    if (!response.ok) {
        if (response.status === 409) {
            throw new ConflictException("User already exists");
        }else {
            throw new Error("Failed to register user");
        }
    }

    const data = await response.json() as ResponseDTO<RegisterResponseDTO>;

    if (!data.success) {
        throw new ServerException(data?.error?.description)
    }

    return data.data!!
}