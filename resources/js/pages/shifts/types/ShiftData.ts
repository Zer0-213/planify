import { Shift } from '@/pages/shifts/types/Shift';
import { WeekDay } from '@/pages/shifts/types/weekday';

export type ShiftData = {
    name: string;
    user_id: number;
    date: string;
    shifts: Record<WeekDay, Shift>;
};
