import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                bosque: '#31401A',
                ocre: '#C09537',
                marron: '#6B5E42',
                tierra: '#261D12',
                oliva: '#656643',
                salvia: '#A1A680',
                beige: '#CBBDA0',
            },
        },
    },

    plugins: [forms],
};
