import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                'brand-cream': '#FAF8F3',
                'brand-sage': '#8FAF6E',
                'brand-sage-dark': '#708E55',
            },
            fontFamily: {
                sans: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                cormorant: ['"Cormorant Garamond"', 'serif'],
                lora: ['"Lora"', 'serif'],
            },
        },
    },
    plugins: [],
};
