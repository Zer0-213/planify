import { User } from '@/types';

export type PageProp = {
    auth: {
        user: User;
    };
    todayShift: {
        start_time: string;
        end_time: string;
        location: string;
    } | null;
    upcomingShifts: Array<{
        id: number;
        date: string;
        start_time: string;
        end_time: string;
        location: string;
    }>;
    notifications: Array<{
        id: number;
        message: string;
    }>;
    weeklyOverview: Array<{
        day: string;
        shift: string | null;
    }>;
};
