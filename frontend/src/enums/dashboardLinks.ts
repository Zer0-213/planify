enum DashboardRoutes {
    HOME = "/dashboard",
    ME = "/dashboard/me",
    SCHEDULE = "/schedule",
    STAFF = "/staff",
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