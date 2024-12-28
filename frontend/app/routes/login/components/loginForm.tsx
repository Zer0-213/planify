import { Form, useActionData, useNavigation } from "react-router";
import CustomButton from "~/components/ui/Button";

const LoginForm = () => {
  const actionData = useActionData();
  const navigation = useNavigation();

  return (
    <Form method="post" className="space-y-4">
      <div>
        <label
          htmlFor="username"
          className="block text-sm font-medium text-gray-700"
        >
          Username
        </label>
        <input
          type="text"
          name="email"
          id="username"
          placeholder="Enter your username"
          className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required
        />
      </div>
      <div>
        <label
          htmlFor="password"
          className="block text-sm font-medium text-gray-700"
        >
          Password
        </label>
        <input
          type="password"
          name="password"
          id="password"
          placeholder="Enter your password"
          className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required
        />
      </div>
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
          Login
        </CustomButton>
      </div>
    </Form>
  );
};

export default LoginForm;
