import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Laravel default
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',

        // Blade utama kamu
        './resources/views/**/*.blade.php',

        // 🔥 PENTING: JS / Alpine / Vue
        './resources/js/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
    ],

    // 🔥 OPTIONAL (kalau ada class dinamis)
    safelist: [
        'bg-red-500',
        'bg-green-500',
        'bg-blue-500',
        'text-white',
        'text-black',
        'flex',
        'grid',
        'hidden',
        'block',
    ],
};
