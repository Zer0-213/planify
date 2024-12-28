enum DashboardRoutes {
    HOME = "/dashboard",
    ME = "/dashboard/me",
    SCHEDULE = "/dashboard/schedule",
    STAFF = "/dashboard/staff",
}

type DashboardRoutesType = {
    name: string;
    route: DashboardRoutes;
};
export const dashboardRoutes: DashboardRoutesType[] = [
    {
        name: "Home",
        route: DashboardRoutes.HOME,
    },
    {
        name: "Staff",
        route: DashboardRoutes.STAFF,
    },
    {
        name: "Schedule",
        route: DashboardRoutes.SCHEDULE,
    },
];
