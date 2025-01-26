export type RegisterDTO = {
    name: string;
    email: string;
    password: string;
    dateOfBirth: string;
}

export type RegisterResponseDTO = {
    token: string;
    expiresAt: string;
}