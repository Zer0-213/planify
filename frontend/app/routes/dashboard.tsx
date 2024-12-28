import DashboardNavbar from "~/components/ui/navBars/dashboardNavbar";
import {Outlet, redirect} from "react-router";
import {checkAuthCookie} from "~/utils/cookies";

export const loader = async ({request}: { request: Request }) => {
    try {
        const sessionId = await checkAuthCookie(request);
        if (!sessionId) {
            return redirect("/login");
        }

    } catch (e) {
        console.error("Loader error:", e);
        return redirect("/error");
    }
}

const Dashboard = () => {
    return (
        <>
            <DashboardNavbar/>
            <Outlet/>
        </>
    );
};

export default Dashboard;
