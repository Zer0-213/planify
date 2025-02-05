type Props = {
    label: string;
    htmlFor: string;
    type?: string;
    name: string;
    placeholder?: string;
    required?: boolean;
    value?: string;
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
};

const CustomInput = ({
                         label,
                         htmlFor,
                         type = "text",
                         name,
                         placeholder = "Enter Value",
                         required,
                         value,
                         onChange
                     }: Props) => {
    return (
        <div className="flex flex-col mb-3">
            <label htmlFor={htmlFor} className="text-sm font-medium text-gray-700">
                {label}
            </label>
            <input
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                type={type}
                id={htmlFor}
                name={name}
                placeholder={placeholder}
                required={required}
                value={value}
                onChange={onChange}
            />
        </div>
    );
};

export default CustomInput;