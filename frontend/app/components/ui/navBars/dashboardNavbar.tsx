import React from "react";
import {NavLink} from "react-router";
import {dashboardRoutes} from "~/enums/dashboardLinks";

const getNavLinkClass = (isActive: boolean) =>
    isActive
        ? "text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 dark:text-white md:dark:text-blue-500 px-4 py-2 rounded-md"
        : "text-gray-700 hover:text-blue-500 px-4 py-2 rounded-md";

const DashboardNavbar = () => {
    const NavLinks = dashboardRoutes.map((route, index) => (
        <NavLink
            key={route.name}
            to={route.route}
            className={({isActive}) => getNavLinkClass(isActive)}
            end
        >
            {route.name}
        </NavLink>
    ));

    return (
        <nav className="bg-white border-gray-200 dark:bg-gray-900">
            <div className="flex flex-row justify-center w-full shadow-md">
                {NavLinks}
            </div>
        </nav>
    );
};

export default DashboardNavbar;
