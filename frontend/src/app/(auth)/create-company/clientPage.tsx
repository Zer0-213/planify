'use client'

import {AuthState} from "@/src/actions/auth/state/authState";
import {useActionState, useState} from "react";
import {submitCompanyAction} from "@/src/actions/auth";
import {Option} from "@/src/types/options";
import {companyTypes} from "@/src/app/(auth)/create-company/constants/options";
import FormModal from "@/src/components/ui/modals/formModal";
import CustomInput from "@/src/components/ui/inputs/customInput";
import FormLabel from "@/src/components/ui/texts/formLabel";
import CustomDropdown from "@/src/components/ui/customDropDown";
import CustomButton from "@/src/components/ui/customButton";
import ErrorText from "@/src/components/ui/texts/errorTexts";
import {CreateCompanyProps} from "@/src/app/(auth)/create-company/types/CreateCompanyProps";

const CreateCompany = ({user, error}: CreateCompanyProps) => {
    const initialState: AuthState = {
        error: null
    }

    const [formState, formAction, isPending] = useActionState(submitCompanyAction, initialState)
    const [selectedCompanyType, setSelectedCompanyType] = useState<Option>(companyTypes[0]);


    return (
        <FormModal header="Create Company">
            <form action={formAction} className="mt-6">
                <div className="flex flex-col gap-5">
                    <CustomInput htmlFor="companyName"
                                 label="Company Name"
                                 name="companyName"
                                 required={true}
                                 placeholder="Enter Company Name"
                    />

                    <CustomInput htmlFor="companyAddress"
                                 label="Company's Address"
                                 name="companyAddress"
                                 required={true}
                                 placeholder="Enter Company Address"
                    />
                    <CustomInput htmlFor="companyNumber"
                                 label="Company's Phone Number"
                                 name="companyNumber"
                                 required={true}
                                 placeholder="Enter Company's Number"
                    />
                    <FormLabel htmlFor={"companyType"}>
                        Company Type:
                        <CustomDropdown name="companyType"
                                        options={companyTypes}
                                        onChange={setSelectedCompanyType}
                                        value={selectedCompanyType.value}/>
                    </FormLabel>
                    <CustomButton type={"submit"} isLoading={isPending} disabled={isPending}/>
                    {formState.error && <ErrorText text={formState.error}/>}
                </div>
            </form>
        </FormModal>
    );
}

export default CreateCompany;