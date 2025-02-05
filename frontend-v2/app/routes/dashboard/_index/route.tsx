import {useFetcher, useLoaderData} from "react-router";
import Header from "~/components/ui/texts/header";
import DashboardCard from "~/routes/dashboard/_index/dashboardCard";
import CustomTable from "~/components/table/CustomTable";
import {useEffect, useMemo, useState} from "react";
import {createColumns} from "~/routes/dashboard/_index/columns"
import {checkCookie} from "~/utils/cookies";
import {getShifts} from "~/services/getShifts";

export async function loader({request}: { request: Request }) {
    await checkCookie(request);

    const today = new Date();
    const currentDay = today.getDay();
    const firstDayOfWeek = new Date(today.setDate(today.getDate() - currentDay + (currentDay === 0 ? -6 : 1)));
    const lastDayOfWeek = new Date(today.setDate(firstDayOfWeek.getDate() + 6));

    const shifts = await getShifts(firstDayOfWeek, lastDayOfWeek);

    return {
        todayShifts: 10,
        employeesScheduled: 8,
        uncoveredShifts: 2,
        allShifts: shifts.shifts ? shifts.shifts : null,
        error: shifts.error ? shifts.error : null,
        weekStart: firstDayOfWeek, // Pass week start date
    };
}

const getDashboardData = (todayShifts: number, employeesScheduled: number, uncoveredShifts: number) => [
    {
        header: "Staff Count Today",
        text: todayShifts.toString(),
        textColor: "blue" as "blue",
    },
    {
        header: "Employees Scheduled",
        text: employeesScheduled.toString(),
        textColor: "green" as "green",
    },
    {
        header: "Uncovered Shifts",
        text: uncoveredShifts.toString(),
        textColor: "red" as "red",
    },
];

const DashboardIndexPage = () => {
    const initialData = useLoaderData();
    const fetcher = useFetcher();

    const [weekStart, setWeekStart] = useState(new Date(initialData.weekStart));
    const [dashboardData, setDashboardData] = useState(initialData);

    useEffect(() => {
        fetcher.load(`/dashboard?week=${weekStart.toISOString()}`);
    }, [weekStart]);

    useEffect(() => {
        if (fetcher.data) {
            setDashboardData(fetcher.data);
        }
    }, [fetcher.data]);

    const columnsMemo = useMemo(() => createColumns(weekStart), [weekStart]);
    const shiftsMemoed = useMemo(() => dashboardData.allShifts, [dashboardData.allShifts]);

    return (
        <div className="p-6 bg-gray-100 min-h-screen">
            <Header text="Dashboard Overview"/>
            {dashboardData.error ? <p className="text-red-500">{dashboardData.error}</p> : (
                <>
                    <div className="flex flex-col sm:flex-row gap-6 mb-2">
                        {getDashboardData(dashboardData.todayShifts, dashboardData.employeesScheduled, dashboardData.uncoveredShifts).map((data, index) => (
                            <DashboardCard key={index} header={data.header} text={data.text}
                                           textColor={data.textColor}/>
                        ))}
                    </div>
                    <div className="flex w-full justify-center items-center mt-6">
                        <CustomTable columns={columnsMemo} data={shiftsMemoed}/>
                    </div>
                </>
            )}
        </div>
    );
};

export default DashboardIndexPage;
