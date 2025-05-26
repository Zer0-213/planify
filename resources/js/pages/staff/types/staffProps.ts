export type StaffProps = {
    id: number;
    name: string;
    email: string;
    phoneNumber: string;
    wage: number;
    permissions: {
        name: string;
        label: string;
    }[];
};
