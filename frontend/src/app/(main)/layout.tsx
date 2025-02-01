import {getUserAction} from "@/src/actions/user/getUserAction";
import DashboardNavbar from "@/src/components/ui/navbars/dashboardNavBar";

const getUser = async () => {

    const user = await getUserAction();
    if ('code' in user) {
        return {
            error: user
        };
    }
    return {
        user
    };

}

export default async function DashboardLayout({children}: { children: React.ReactNode }) {
    const data = await getUser();

    return data.error ? (
        <div>{data.error.message}</div>
    ) : (
        <>
            <DashboardNavbar/>
            <div className="p-6">{children}</div>
        </>
    );
}