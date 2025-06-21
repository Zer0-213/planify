export type StaffProps = {
    id: number;
    name: string;
    email: string;
    phoneNumber: string;
    wage: number;
    role: string;
    edit?: string; // Optional, used for delete action
};
