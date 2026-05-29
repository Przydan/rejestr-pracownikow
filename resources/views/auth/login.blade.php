<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logowanie - Rejestr Pracowników</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-10">
            <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-xl shadow-emerald-200 mb-4 animate-bounce-slow">
                R
            </div>
            <h1 class="text-3xl font-bold text-slate-900 leading-none">Rejestr Pracowników</h1>
            <p class="text-slate-500 mt-2 font-medium">Panel dostępowy dla personelu</p>
        </div>

        <div class="bg-white shadow-2xl shadow-slate-200 rounded-3xl p-8 lg:p-10 border border-slate-100">
            @if(session('error'))
                <div class="mb-6 p-4 text-rose-800 bg-rose-50 border border-rose-100 rounded-2xl text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Adres Email</label>
                    <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}" 
                           class="block w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                           placeholder="np. jan.kowalski@firma.pl">
                    @error('email')
                        <p class="mt-2 text-xs text-rose-600 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Hasło</label>
                        {{-- <a href="#" class="text-[10px] font-bold text-emerald-600 hover:underline uppercase tracking-tighter">Zapomniałeś hasła?</a> --}}
                    </div>
                    <input id="password" name="password" type="password" required 
                           class="block w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-xs text-rose-600 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-lg shadow-emerald-200 text-sm font-bold uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-[0.98]">
                        Zaloguj się do systemu
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center text-slate-400 text-xs mt-10 font-medium">
            &copy; {{ date('Y') }} Rejestr Pracowników. System wewnętrzny.
        </p>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
            50% { transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
    </style>
</body>
</html>
