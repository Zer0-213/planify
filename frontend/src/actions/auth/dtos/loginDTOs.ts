export type LoginDTO = {
    email: string;
    password: string;
}

export type LoginResponseDTO = {
    token: string;
    expiresAt: string;
}