// Mock data loader
import {useLoaderData} from "react-router";
import {CustomCard} from "../../../../components/customCard";

export const loader = async () => {
    const staff = [
        {firstName: "John", lastName: "Doe", email: "john.doe@example.com", role: "Manager"},
        {firstName: "Jane", lastName: "Smith", email: "jane.smith@example.com", role: "Customer Service"},
        {
            firstName: "Alice",
            lastName: "Johnson",
            email: "alice.johnson@example.com",
            role: "Admin",
            phoneNumber: "555-555-5555"
        },
    ];
    return {staff};
};

export default function Employees() {
    const {staff} = useLoaderData();

    return (
        <div className="p-6 max-w-3xl mx-auto">
            <h1 className="text-2xl font-bold mb-4">Employees</h1>
            <div className="space-y-4">
                {staff.map((employee: any, index: number) => (
                    <CustomCard key={index}>
                        <div className="flex flex-row justify-between">
                            <h2 className="text-lg font-semibold">
                                {`${employee.firstName} ${employee.lastName}`}
                            </h2>
                        </div>
                        <p className="text-gray-600">{employee.email}</p>
                        <p className="text-gray-600">{employee.phoneNumber}</p>
                    </CustomCard>
                ))}
            </div>
        </div>
    );
}

