// resources/js/pages/shifts/types/ShiftTypes.ts
export type ShiftTime = {
    id: number | null;
    starts_at: string;
    ends_at: string;
    shift_date: string;
};

export type UserShift = {
    name: string;
    user_id: number;
    shifts: ShiftTime[];
    total_hours: number;
    total_minutes: number;
};

export type WeekDay = 'monday' | 'tuesday' | 'wednesday' | 'thursday' | 'friday' | 'saturday' | 'sunday';
