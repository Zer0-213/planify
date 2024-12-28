import {useLoaderData} from "react-router"

export async function loader() {
    // Server Function:
    // Fetch summary data for:
    // - Total shifts today
    // - Number of employees scheduled
    // - Uncovered shifts
    // This data can be fetched from a database or API.
    return {
        todayShifts: 10,
        employeesScheduled: 8,
        uncoveredShifts: 2,
    };
}

const DashboardIndexPage = () => {
    const {todayShifts, employeesScheduled, uncoveredShifts} = useLoaderData();

    return (
        <div className="p-6 bg-gray-100 min-h-screen">
            <h1 className="text-2xl font-bold mb-4">Dashboard Overview</h1>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {/* Total Shifts Today */}
                <div className="bg-white shadow-md rounded-lg p-4">
                    <h2 className="text-lg font-semibold">Total Shifts Today</h2>
                    <p className="text-3xl font-bold text-blue-500">{todayShifts}</p>
                </div>

                {/* Employees Scheduled */}
                <div className="bg-white shadow-md rounded-lg p-4">
                    <h2 className="text-lg font-semibold">Employees Scheduled</h2>
                    <p className="text-3xl font-bold text-green-500">{employeesScheduled}</p>
                </div>

                {/* Uncovered Shifts */}
                <div className="bg-white shadow-md rounded-lg p-4">
                    <h2 className="text-lg font-semibold">Uncovered Shifts</h2>
                    <p className="text-3xl font-bold text-red-500">{uncoveredShifts}</p>
                </div>
            </div>
        </div>
    );
}

export default DashboardIndexPage;