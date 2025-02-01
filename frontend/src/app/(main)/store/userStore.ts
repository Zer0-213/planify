import {create} from "zustand";

export type UserStoreType = {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
    companyID: number;
    setName: (firstName: string, lastName: string) => void;
    setEmail: (email: string) => void;
    setCompanyID: (companyID: number) => void;
}

export const useUserStore = create<UserStoreType>((set) => ({
    id: 0,
    firstName: "",
    lastName: "",
    email: "",
    companyID: 0,

    setName: (firstName: string, lastName: string) => set({firstName, lastName}),
    setEmail: (email: string) => set({email}),
    setCompanyID: (companyID: number) => set({companyID}),
}));