import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", "ui-sans-serif", "system-ui", "sans-serif"],
                inter: ["inter", "ui-sans-serif", "system-ui", "sans-serif"],
                poppins: [
                    "Poppins",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
            },
        },
    },

    plugins: [forms, require("flowbite/plugin")],
};
