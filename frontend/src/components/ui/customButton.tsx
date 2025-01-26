import {Button} from "flowbite-react";

type Props = {
    children: React.ReactNode;
    onClick?: () => void;
    className?: string;
    disabled?: boolean;
    type?: "button" | "submit" | "reset";
    isLoading?: boolean;
}

const CustomButton = ({children, onClick, disabled, type, isLoading}: Props) => {

    return (
        <Button
            type={type || "button"}
            onClick={onClick}
            disabled={disabled}
            isProcessing={isLoading}
            color={disabled ? "gray" : "blue"}
        >
            {children}
        </Button>
    );
}

export default CustomButton;