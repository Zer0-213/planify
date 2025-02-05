/** @type {import('tailwindcss').Config} */
const flowbite = require("flowbite-react/tailwind");

export default {
    content: ["./app/**/*.{ts,tsx}", flowbite.content()],
    theme: {
        extend: {},
    },
    plugins: [flowbite.plugin(),],
};
