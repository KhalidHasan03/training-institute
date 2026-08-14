/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f2f2ff',
                    100: '#e8e7ff',
                    200: '#d4d1ff',
                    300: '#b6abfc',
                    400: '#9680f8',
                    500: '#7a57f0',
                    600: '#6636e3',
                    700: '#5828c8',
                    800: '#4922a4',
                    900: '#3c1e86',
                    950: '#24115c',
                },
                navy: {
                    50: '#eef0fb',
                    100: '#d8dcf6',
                    200: '#b8bdec',
                    300: '#8b92de',
                    400: '#5d63cc',
                    500: '#3f43b4',
                    600: '#32369a',
                    700: '#2b2d7d',
                    800: '#252763',
                    900: '#1c1d45',
                    950: '#121231',
                },
                accent: {
                    50: '#fff1fe',
                    100: '#ffe3fd',
                    200: '#ffc6fa',
                    300: '#ff9af5',
                    400: '#fb5deb',
                    500: '#ef34d6',
                    600: '#d313bb',
                    700: '#b00d99',
                    800: '#91127e',
                    900: '#781569',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Plus Jakarta Sans', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                soft: '0 2px 12px -2px rgb(0 0 0 / 0.08), 0 1px 2px rgb(0 0 0 / 0.04)',
                lift: '0 8px 28px -6px rgb(0 0 0 / 0.12), 0 2px 8px rgb(0 0 0 / 0.05)',
                glow: '0 0 24px -4px rgb(102 54 227 / 0.45)',
                'glow-lg': '0 0 48px -8px rgb(102 54 227 / 0.55)',
                'glow-purple': '0 0 24px -4px rgb(239 52 214 / 0.4)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #1c1d45 0%, #3c1e86 45%, #6636e3 75%, #d313bb 130%)',
                'brand-gradient-soft': 'linear-gradient(135deg, #e8e7ff 0%, #d4d1ff 40%, #ffc6fa 100%)',
                'brand-text': 'linear-gradient(100deg, #7a57f0 0%, #ef34d6 60%, #ff9af5 100%)',
                'grid-lines': 'linear-gradient(to right, rgb(255 255 255 / 0.06) 1px, transparent 1px), linear-gradient(to bottom, rgb(255 255 255 / 0.06) 1px, transparent 1px)',
                'grid-lines-dark': 'linear-gradient(to right, rgb(28 29 69 / 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgb(28 29 69 / 0.05) 1px, transparent 1px)',
            },
            animation: {
                'float': 'float 8s ease-in-out infinite',
                'float-slow': 'float 12s ease-in-out infinite',
                'pulse-glow': 'pulse-glow 4s ease-in-out infinite',
                'aurora': 'aurora 18s ease-in-out infinite alternate',
                'shine': 'shine 2.5s linear infinite',
                'marquee': 'marquee 30s linear infinite',
                'fade-up': 'fade-up 0.7s ease-out both',
                'blink': 'blink 1.4s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) translateX(0)' },
                    '50%': { transform: 'translateY(-18px) translateX(10px)' },
                },
                'pulse-glow': {
                    '0%, 100%': { opacity: '0.55', transform: 'scale(1)' },
                    '50%': { opacity: '1', transform: 'scale(1.06)' },
                },
                aurora: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)', opacity: '0.55' },
                    '50%': { transform: 'translate(60px, -40px) scale(1.15)', opacity: '0.85' },
                    '100%': { transform: 'translate(-40px, 30px) scale(1.05)', opacity: '0.65' },
                },
                shine: {
                    '0%': { transform: 'translateX(-120%) skewX(-20deg)' },
                    '60%, 100%': { transform: 'translateX(220%) skewX(-20deg)' },
                },
                marquee: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                blink: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.35' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};