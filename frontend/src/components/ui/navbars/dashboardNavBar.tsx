"use client";

import Link from "next/link";
import {usePathname} from "next/navigation";
import {dashboardRoutes} from "@/src/enums/dashboardLinks";

const getNavLinkClass = (isActive: boolean) =>
    isActive ? "text-blue-500 font-bold border-b-2 border-blue-500" : "text-gray-500";

const DashboardNavbar = () => {
    const pathname = usePathname();

    return (
        <nav className="w-full bg-white border-gray-200 dark:bg-gray-900">
            <div className="flex flex-row justify-center w-full shadow-md gap-6 p-2">
                {dashboardRoutes.map((route) => {
                    const isActive = pathname === route.route;
                    return (
                        <Link key={route.name} href={route.route} className={getNavLinkClass(isActive)}>
                            {route.name}
                        </Link>
                    );
                })}
            </div>
        </nav>
    );
};

export default DashboardNavbar;
