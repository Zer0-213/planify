import {Form, Navigation} from "react-router";
import React, {useState} from "react";
import CustomButton from "~/components/ui/Button";
import {Option} from "~/types/options";
import {companyTypes} from "~/components/pages/CreateCompany/constants/dropdownItems";
import CustomDropdown from "~/components/ui/Dropdown";

type Props = {
    navigation: Navigation;
};

const CreateCompany = ({navigation}: Props) => {
    const [companyName, setCompanyName] = useState("");
    const [companyAddress, setCompanyAddress] = useState("");
    const [phoneNumber, setPhoneNumber] = useState("");
    const [companyType, setCompanyType] = useState<Option>(companyTypes[0]);

    return (
        <section className="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
            <Form method="post" className="flex flex-col justify-center items-cente gap-4">
                <h1 className="text-2xl font-bold text-center mb-6">Enter Company Details</h1>
                <div>
                    <label> Company Name:</label>
                    <input
                        className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        type="text"
                        name="companyName"
                        value={companyName}
                        onChange={(e) => setCompanyName(e.target.value)}
                        required/>
                </div>
                <div>
                    <label>Company Address:</label>
                    <input
                        className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        type="text"
                        name="companyAddress"
                        value={companyAddress}
                        onChange={(e) => setCompanyAddress(e.target.value)}
                        required
                    />
                </div>
                <div>
                    <label>Phone Number</label>
                    <input
                        className="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        type="text"
                        name="companyPhone"
                        value={phoneNumber}
                        onChange={(e) => setPhoneNumber(e.target.value)}
                        required={false}
                    />
                </div>
                <div>
                    <label>Company Type:</label>
                    <CustomDropdown options={companyTypes} value={companyType.value} onChange={setCompanyType}/>
                </div>
                <div className="flex justify-center">
                    <CustomButton type="submit" isProcessing={navigation.state === "submitting"}>Submit</CustomButton>
                </div>
            </Form>
        </section>
    );
}

export default CreateCompany;