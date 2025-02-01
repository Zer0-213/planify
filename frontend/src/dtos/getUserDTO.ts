import {ErrorDTO} from "@/src/utils/dtos/errorDTO";

export type GetUserDTO = {
    Id: number
    firstName: string;
    lastName: string;
    email: string;
    phoneNumber: string;
    role: string;
    status: string;
    companyId: number;
    createdAt: Date | undefined;
    updatedAt: Date | undefined;
    error?: ErrorDTO;
}