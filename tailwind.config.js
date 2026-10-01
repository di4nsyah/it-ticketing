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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', ...defaultTheme.fontFamily.sans],
            },

            // One warm neutral ramp plus a single accent. Nothing else.
            colors: {
                canvas: '#EFEBE1',
                surface: '#FFFFFF',
                sunken: '#F4F1E9',

                ink: {
                    DEFAULT: '#2C2B28',
                    soft: '#6E6B63',
                    faint: '#A5A196',
                },

                line: {
                    DEFAULT: '#E2DDD0',
                    strong: '#CFC9B8',
                },

                accent: {
                    DEFAULT: '#E8E64A',
                    soft: '#F3F192',
                    deep: '#CFCB2B',
                },

                // The only permitted semantic exception: errors and destructive
                // actions. Nothing decorative uses it.
                danger: '#B3261E',
            },

            borderRadius: {
                '4xl': '2rem',
            },

            // Structure is carried by hairlines; these are barely-there lifts.
            boxShadow: {
                hairline: '0 0 0 1px rgba(44, 43, 40, 0.04)',
                pop: '0 1px 2px rgba(44, 43, 40, 0.04), 0 14px 30px -18px rgba(44, 43, 40, 0.22)',
            },

            keyframes: {
                rise: {
                    from: { opacity: '0', transform: 'translateY(10px)' },
                    to: { opacity: '1', transform: 'none' },
                },
            },

            animation: {
                rise: 'rise 0.5s cubic-bezier(0.22, 1, 0.36, 1) both',
            },
        },
    },

    plugins: [forms],
};
