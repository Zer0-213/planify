import {Option, Options} from "~/types/options";
import React from "react";

type SelectProps = {
    options: Options;
    onChange: (value: Option) => void;
    value: string | number; // Must align with Option['value'] type.
};

const CustomDropdown = ({options, onChange, value}: SelectProps) => {
    return (
        <div className="relative w-full">
            <select
                id="countries"
                value={value}
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                onChange={(e) => {
                    const selectedValue =
                        typeof options[0].value === "number"
                            ? parseInt(e.target.value, 10)
                            : e.target.value;
                    const selectedOption = options.find(option => option.value === selectedValue);
                    if (selectedOption) {
                        onChange(selectedOption);
                    }
                }}
            >
                {options.map((option, index) => (
                    <option key={index} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default CustomDropdown;
