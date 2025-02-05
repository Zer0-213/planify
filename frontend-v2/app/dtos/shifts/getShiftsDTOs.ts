import type {ErrorDTO} from "~/dtos/shared/errorDTO";

export type GetShiftsDTO = {
    shifts: ShiftsDTO[];
    error: ErrorDTO;
};

type ShiftsDTO = {
    userId: number;
    userName: string;
    monday?: string;
    tuesday?: string;
    wednesday?: string;
    thursday?: string;
    friday?: string;
    saturday?: string;
    sunday?: string;
};