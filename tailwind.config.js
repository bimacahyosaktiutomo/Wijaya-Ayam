import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        screens: {
            xs: "540px",
            sm: "640px",
            md: "768px",
            lg: "1024px",
            xl: "1280px",
            "2xl": "1536px",
        },
    },

    plugins: [
        forms,
        require('daisyui'),
        require('flowbite/plugin'),
        function({ addUtilities }) {
            const newUtilities = {
                '.hover-anim': {
                transition : '300ms ease-in-out',
                },
                '.no-scrollbar::-webkit-scrollbar': {
                display: 'none',
                },
                '.no-scrollbar': {
                '-ms-overflow-style': 'none', // IE and Edge
                'scrollbar-width': 'none',    // Firefox
                },
            };
        
            addUtilities(newUtilities, ['responsive', 'hover']);
            },
    ],
};
