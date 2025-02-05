import Header from "~/components/ui/texts/header";
import React from "react";
import {Form} from "react-router";

type Props = {
    children: React.ReactNode;
    header?: string;
    actionName?: string
}

const FormModal = ({children, header, actionName}: Props) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-100">
            <div className="w-full max-w-sm p-6 bg-white rounded-2xl shadow-md">
                {header && <Header text={header}/>}
                <Form className="mt-6" method="POST">
                    {children}
                </Form>
            </div>
        </div>
    );
}

export default FormModal;