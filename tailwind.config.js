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
                sans: ['"Book Antiqua"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Semantic Colors
                primary: {
                    50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                    500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554',
                    DEFAULT: '#2563EB',
                },
                secondary: {
                    50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8',
                    500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a', 950: '#020617',
                    DEFAULT: '#475569',
                },
                success: {
                    50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399',
                    500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b', 950: '#022c22',
                    DEFAULT: '#10B981',
                },
                warning: {
                    50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d', 400: '#fbbf24',
                    500: '#f59e0b', 600: '#d97706', 700: '#b45309', 800: '#92400e', 900: '#78350f', 950: '#451a03',
                    DEFAULT: '#F59E0B',
                },
                danger: {
                    50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171',
                    500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d', 950: '#450a0a',
                    DEFAULT: '#EF4444',
                },
                info: {
                    50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8',
                    500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e', 950: '#082f49',
                    DEFAULT: '#0EA5E9',
                },
                neutral: {
                    50: '#fafafa', 100: '#f5f5f5', 200: '#e5e5e5', 300: '#d4d4d4', 400: '#a3a3a3',
                    500: '#737373', 600: '#525252', 700: '#404040', 800: '#262626', 900: '#171717', 950: '#0a0a0a',
                    DEFAULT: '#737373',
                },
            },
            fontSize: {
                'caption': ['0.75rem', { lineHeight: '1.25rem', fontWeight: '400' }], // 12px
                'small': ['0.875rem', { lineHeight: '1.5rem', fontWeight: '400' }], // 14px
                'body': ['1rem', { lineHeight: '1.5rem', fontWeight: '400' }], // 16px
                'h3': ['1.25rem', { lineHeight: '1.75rem', fontWeight: '600' }], // 20px
                'h2': ['1.5rem', { lineHeight: '2rem', fontWeight: '700' }], // 24px
                'h1': ['2.25rem', { lineHeight: '2.5rem', fontWeight: '800' }], // 36px
                'hero': ['3rem', { lineHeight: '1', fontWeight: '900' }], // 48px
                'xs': ['0.75rem', { lineHeight: '1.25rem' }],
                'sm': ['0.875rem', { lineHeight: '1.5rem' }],
                'base': ['1rem', { lineHeight: '1.75rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
            },
            spacing: {
                '4': '1rem',
                '8': '2rem',
                '12': '3rem',
                '16': '4rem',
                '20': '5rem',
                '24': '6rem',
                '32': '8rem',
                '40': '10rem',
                '48': '12rem',
                '64': '16rem',
            },
            borderRadius: {
                'xl': '0.75rem',  // Button, Input
                '2xl': '1rem',    // Card
                '3xl': '1.5rem',  // Modal
                'full': '9999px', // Avatar
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },

    plugins: [forms],
};
