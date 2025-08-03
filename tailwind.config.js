import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    
    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                cosmic: {
                    bg: 'rgb(var(--cosmic-bg) / <alpha-value>)',
                    'bg-secondary': 'rgb(var(--cosmic-bg-secondary) / <alpha-value>)',
                    text: 'rgb(var(--cosmic-text) / <alpha-value>)',
                    'text-secondary': 'rgb(var(--cosmic-text-secondary) / <alpha-value>)',
                    'text-muted': 'rgb(var(--cosmic-text-muted) / <alpha-value>)',
                    border: 'rgb(var(--cosmic-border) / <alpha-value>)',
                    'card-bg': 'rgb(var(--cosmic-card-bg) / <alpha-value>)',
                    'card-border': 'rgb(var(--cosmic-card-border) / <alpha-value>)',
                    primary: 'rgb(var(--cosmic-primary) / <alpha-value>)',
                    'primary-hover': 'rgb(var(--cosmic-primary-hover) / <alpha-value>)',
                    accent: 'rgb(var(--cosmic-accent) / <alpha-value>)',
                    'accent-hover': 'rgb(var(--cosmic-accent-hover) / <alpha-value>)',
                },
            },
        },
    },

    plugins: [forms],
};
