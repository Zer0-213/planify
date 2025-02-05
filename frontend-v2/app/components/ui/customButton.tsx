import {Button} from "flowbite-react";

type Props = {
    onClick?: () => void;
    className?: string;
    disabled?: boolean;
    type?: "button" | "submit" | "reset";
    isLoading?: boolean;
    text?: string;
}

const CustomButton = ({onClick, disabled, type, isLoading, text = "Submit"}: Props) => {
    return (
        <Button
            type={type || "button"}
            onClick={onClick}
            disabled={disabled}
            isProcessing={isLoading}
            color={disabled ? "gray" : "blue"}
        >
            {text}
        </Button>
    );
}

export default CustomButton;