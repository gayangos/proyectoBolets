import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

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

    plugins: [forms, daisyui],

    // Tema de DaisyUI con la paleta de la web.
    daisyui: {
        themes: [
            {
                bolets: {
                    primary: '#C09537',
                    'primary-content': '#FFFFFF',
                    secondary: '#31401A',
                    'secondary-content': '#FFFFFF',
                    accent: '#A1A680',
                    neutral: '#6B5E42',
                    'base-100': '#FFFFFF',
                    'base-200': '#F4EFE4',
                    'base-300': '#CBBDA0',
                    'base-content': '#261D12',
                },
            },
        ],
        logs: false,
    },
};