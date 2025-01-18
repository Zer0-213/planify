import {Form, useActionData, useNavigation} from "react-router";
import CustomButton from "~/components/ui/Button";


const SignUpForm = () => {
    const actionData = useActionData();
    const navigation = useNavigation();

    return (
        <Form method="post" className="space-y-4">
                <span className="flex flex-row space-x-4 justify-center">
                    <label
                        htmlFor="firstName"
                        className="block text-sm font-medium text-gray-700"
                    >
                        First Name
                                            <input
                                                type="text"
                                                name="firstName"
                                                id="firstName"
                                                placeholder="Enter your first name"
                                                className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                required
                                            />
                    </label>
                    <label
                        htmlFor="lastName"
                        className="block text-sm font-medium text-gray-700"
                    >
                        Last Name
                                            <input
                                                type="text"
                                                name="lastName"
                                                id="lastName"
                                                placeholder="Enter your last name"
                                                className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                required
                                            />
                    </label>
                </span>
            <label
                htmlFor="email"
                className="block text-sm font-medium text-gray-700"
            >
                Email
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email"
                    className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </label>
            <label
                htmlFor="password"
                className="block text-sm font-medium text-gray-700"
            >
                Password
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter your password"
                    className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </label>
            <label htmlFor="dateOfBirth"
                   className="block text-sm font-medium text-gray-700">
                Date of Birth
                <input
                    type="date"
                    name="dateOfBirth"
                    id="dateOfBirth"
                    className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-center"
                    required
                />
            </label>
            {actionData?.error && (
                <div className="text-red-600 text-sm text-center">
                    {actionData.error}
                </div>
            )}
            <div className="flex justify-center">
                <CustomButton
                    type="submit"
                    isProcessing={navigation?.state === "submitting"}
                >
                    Sign Up
                </CustomButton>
            </div>
        </Form>
    );

}

export default SignUpForm
