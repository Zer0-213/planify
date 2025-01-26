type Props = {
    children: React.ReactNode;
    htmlFor: string;
}

const FormLabel = ({children, htmlFor}: Props) => {

    return (
        <label htmlFor={htmlFor} className="block text-sm font-medium text-gray-700 mb-6">
            {children}
        </label>
    );


}

export default FormLabel;