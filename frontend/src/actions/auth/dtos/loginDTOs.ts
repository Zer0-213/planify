export type LoginDTO = {
    email: string;
    password: string;
}

export type LoginResponseDTO = {
    userId: number;
    token: string;
    expiresAt: string;
    companyId: number | null;
}