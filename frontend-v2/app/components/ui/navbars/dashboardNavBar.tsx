import {NavLink} from "react-router";
import {dashboardRoutes} from "~/enums/dashboardLinks";

const getNavLinkClass = (isActive: boolean) =>
    isActive ? "text-blue-500 font-bold border-b-2 border-blue-500" : "text-gray-500";

const DashboardNavbar = () => {

    return (
        <nav className="w-full bg-white border-gray-200 dark:bg-gray-900">
            <div className="flex flex-row justify-center w-full shadow-md gap-6 p-2">
                {dashboardRoutes.map((route) => {
                    return (
                        <NavLink key={route.name}
                                 to={route.route}
                                 className={({isActive}) => getNavLinkClass(isActive)}
                                 end>
                            {route.name}
                        </NavLink>
                    );
                })}
            </div>
        </nav>
    );
};

export default DashboardNavbar;
