import React from "react";
import {Button} from "flowbite-react";

type ButtonProps = {
    children: React.ReactNode;
    onClick?: () => void;
    type: "submit" | "reset" | "button";
    variant?: ButtonColor;
    isProcessing?: boolean;
};

export enum ButtonColor {
    PRIMARY = "text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 ",
    SECONDARY = "text-gray-700 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-600 shadow-lg shadow-gray-300/50 dark:shadow-lg dark:shadow-gray-600/80 ",
    DANGER = "text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 ",
}

const CustomButton = ({
                          children,
                          onClick,
                          type,
                          variant = ButtonColor.PRIMARY,
                          isProcessing = false,
                      }: ButtonProps) => {
    return (
        <Button
            onClick={onClick}
            type={type}
            className={variant}
            isProcessing={isProcessing}
        >
            {children}
        </Button>
    );
};

export default CustomButton;
