const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                // sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                sans: ["Lexend Deca", 'Outfit', 'Poppins', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui: {
        themes: [
            {
                pbtheme: {
                    primary: "#570DF8",
                    secondary: "#F000B8",
                    accent: "#37CDBE",
                    neutral: "#3D4451",
                    "base-100": "#FFFFFF",
                    info: "#3ABFF8",
                    success: "#36D399",
                    warning: "#FBBD23",
                    error: "#F87272",
                    green: "#19743b",
                    red: "#F87272",
                    accent: "#806043",
                    neutral: "#3D4451",
                    "base-100": "#FFFFFF",
                },
                extend: {
                    colors: {
                        primary: "#570DF8",
                        secondary: "#F000B8",
                        accent: "#37CDBE",
                        neutral: "#3D4451",
                        "base-100": "#FFFFFF",
                        info: "#3ABFF8",
                        success: "#36D399",
                        warning: "#FBBD23",
                        error: "#F87272",
                        green: "#19743b",
                        red: "#F87272",
                        accent: "#806043",
                        neutral: "#3D4451",
                        "base-100": "#FFFFFF",
                    },
                },
            },
        ],
    },

    plugins: [require("daisyui"), require("flowbite/plugin"), require('flowbite-typography')],
};
