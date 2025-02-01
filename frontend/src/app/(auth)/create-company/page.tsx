import CreateCompany from "@/src/app/(auth)/create-company/clientPage";
import {CreateCompanyProps} from "@/src/app/(auth)/create-company/types/CreateCompanyProps";
import {getUserAction} from "@/src/actions/user/getUserAction";


const getUserData = async (): Promise<CreateCompanyProps> => {
    const user = await getUserAction();
    if ('code' in user) {
        return {
            error: user
        };
    }
    return {
        user
    };
};

export default async function CreateCompanyPage() {
    const data = await getUserData();
    return <CreateCompany user={data.user} error={data.error}/>
}

