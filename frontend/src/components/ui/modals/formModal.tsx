import Header from "@/src/components/ui/texts/header";

type Props = {
    children: React.ReactNode;
    header?: string;
}

const FormModal = ({children, header}: Props) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-100">
            <div className="w-full max-w-sm p-6 bg-white rounded-2xl shadow-md">
                {header && <Header text={header}/>}
                {children}
            </div>
        </div>
    );
}

export default FormModal;