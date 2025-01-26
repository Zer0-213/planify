type Props = {
    children: React.ReactNode;
}

const FormModal = ({children}: Props) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-100">
            <div className="w-full max-w-sm p-6 bg-white rounded-2xl shadow-md">
                {children}
            </div>
        </div>
    );
}

export default FormModal;