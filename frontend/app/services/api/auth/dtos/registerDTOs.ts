export type RegisterRequestDTO = {
    firstName: string
    lastName: string
    email: string
    password: string
    dateOfBirth: Date
}

export type RegisterResponseDTO = {
    token: string
    expiresAt: Date
}