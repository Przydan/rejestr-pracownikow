<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rejestr Pracowników - Logowanie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full">
        <!-- Application Branding -->
        <div class="flex flex-col items-center mb-10 text-center">
            <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-xl shadow-emerald-100 mb-4 transition-transform hover:scale-105">
                R
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Rejestr Pracowników</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">System zarządzania personelem</p>
        </div>

        @auth
            <!-- Logged In State -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center space-y-6">
                <div>
                    <p class="text-slate-500 font-medium mb-1">Jesteś zalogowany jako</p>
                    <p class="text-lg font-bold text-slate-900">{{ auth()->user()->name }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="block w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-50 text-sm font-bold uppercase tracking-widest transition-all">
                    Przejdź do aplikacji
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition-colors uppercase tracking-widest">
                        Wyloguj się
                    </button>
                </form>
            </div>
        @else
            <!-- Simple Login Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-10">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Adres Email</label>
                        <input id="email" name="email" type="email" required autofocus
                               class="block w-full px-4 py-3.5 bg-slate-50 border-slate-100 rounded-xl text-slate-900 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-300"
                               placeholder="twoj@email.pl">
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Hasło</label>
                        <input id="password" name="password" type="password" required
                               class="block w-full px-4 py-3.5 bg-slate-50 border-slate-100 rounded-xl text-slate-900 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-300"
                               placeholder="••••••••">
                    </div>

                    @if ($errors->any())
                        <div class="p-3 text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 rounded-xl uppercase tracking-widest leading-relaxed">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-50 text-xs font-bold uppercase tracking-widest transition-all">
                        Zaloguj się
                    </button>
                </form>
            </div>
        @endauth

        <footer class="mt-12 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                &copy; {{ date('Y') }} Rejestr Pracowników
            </p>
        </footer>
    </div>
</body>
</html>
