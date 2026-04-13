<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SISPENTRA') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                200: '#bfdbfe',
                                300: '#93c5fd',
                                400: '#60a5fa',
                                500: '#3b82f6',
                                600: '#2563eb',
                                700: '#1d4ed8',
                                800: '#1e40af',
                                900: '#1e3a8a',
                            },
                        }
                    }
                }
            }
        </script>
        <style>
             input[type='email'], input[type='password'], input[type='text'] {
                transition: all 0.2s;
            }
            input:focus {
                box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
                border-color: #2563eb !important;
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 dark:bg-slate-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="text-center">
                <a href="/" class="group flex flex-col items-center">
                    <x-application-logo class="block h-16 w-auto fill-current text-slate-800 dark:text-white group-hover:scale-110 transition-transform duration-300 mb-4" />
                    <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter uppercase leading-none">
                        SIS<span class="text-primary-600">PENTRA</span>
                    </h1>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-[0.1em] sm:tracking-[0.3em] uppercase mt-1 max-w-[280px] sm:max-w-none text-center">Sistem Pencatatan Transaksi Kas Terpadu</p>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-white dark:bg-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden sm:rounded-2xl border border-slate-100 dark:border-slate-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
