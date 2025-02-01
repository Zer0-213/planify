export type RegisterDTO = {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
    dateOfBirth: string;
}

export type RegisterResponseDTO = {
    token: string;
    expiresAt: string;
}