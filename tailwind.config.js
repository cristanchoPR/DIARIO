import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                aldia: {
                    primary: '#5A8FDB',
                    primaryDark: '#3E6FB8',
                    navy: '#16305C',
                    warm: '#F3D9AE',
                    warmDark: '#E3B77E',
                    success: '#2FAE7A',
                    danger: '#E5584D',
                    bgLight: '#F7F9FC',
                    bgCard: '#FFFFFF',
                    borderLight: '#E3E8F0',
                    textMain: '#1B2437',
                    textSec: '#6B7686',
                }
            }
        },
    },

    plugins: [forms],
};
