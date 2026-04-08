<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="text-slate-700 dark:text-slate-300 font-semibold mb-1" />
            <x-text-input id="email" class="block w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" value="Kata Sandi" class="text-slate-700 dark:text-slate-300 font-semibold mb-1" />

            <x-text-input id="password" class="block w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Masukkan kata sandi" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 dark:bg-slate-900 text-primary-600 shadow-sm focus:ring-primary-500" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400 font-medium">Ingat Saya</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif

            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg shadow-sm shadow-primary-500/30 transition-all focus:ring-4 focus:ring-primary-500/20 active:scale-95">
                Masuk Sistem
            </button>
        </div>
    </form>
</x-guest-layout>
