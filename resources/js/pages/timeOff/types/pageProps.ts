export type PageProps = {
    userTimeOff: Array<UserTimeOffRequest>;
    companyTimeOffRequests: Array<CompanyTimeOffRequest> | null;
};

type TimeOffStatus = 'pending' | 'approved' | 'rejected';

type UserTimeOffRequest = {
    id: number;
    company_user_id: number;
    start_date: string;
    start_time: string | null;
    end_date: string;
    end_time: string | null;
    is_full_day: boolean;
    status: TimeOffStatus;
    reason: string | null;
    admin_notes: string | null;
    approved_by: number | null;
    approved_at: string | null;
    created_at: string;
    updated_at: string;
};

type CompanyTimeOffRequest = {
    id: number;
    company_user_id: number;
    start_date: string;
    start_time: string | null;
    end_date: string;
    end_time: string | null;
    is_full_day: boolean;
    status: TimeOffStatus;
    reason: string | null;
    admin_notes: string | null;
    approved_by: number | null;
    approved_at: string | null;
    created_at: string;
    updated_at: string;
    company_user: {
        id: number;
    };
};
