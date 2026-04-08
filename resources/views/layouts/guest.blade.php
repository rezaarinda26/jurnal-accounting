<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Akuntansi Operasional') }}</title>

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
                <a href="/" class="group">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-600 rounded-xl mb-4 shadow-lg shadow-primary-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter uppercase leading-none">
                        Sistem <span class="text-primary-600">Akuntansi</span>
                    </h1>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-[0.3em] uppercase mt-1">Operasional</p>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-white dark:bg-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden sm:rounded-2xl border border-slate-100 dark:border-slate-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
