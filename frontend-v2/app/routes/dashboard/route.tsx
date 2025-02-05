import {Outlet} from "react-router";
import DashboardNavbar from "~/components/ui/navbars/dashboardNavBar";
import {checkCookie} from "~/utils/cookies";

export async function loader({request}: { request: Request }) {
    await checkCookie(request);
}

export default function DashboardLayout() {
    return (
        <div className="min-h-screen bg-gray-100">
            <DashboardNavbar/>
            <Outlet/>
        </div>
    );
}