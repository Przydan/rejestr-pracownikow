<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-emerald-100 selection:text-emerald-900">
    @auth
    @php
        $user = auth()->user();
        $isAdmin = $user->hasRole('administrator');
        $isManager = $user->hasRole('kierownik');
        $isManagerOrAdmin = $isAdmin || $isManager;
        $homeRoute = $isAdmin ? 'admin.dashboard' : ($isManager ? 'manager.dashboard' : 'dashboard');
    @endphp

    <!-- Top Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <!-- Logo -->
                    <a href="{{ route($homeRoute) }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center transition-all group-hover:rotate-6 group-hover:bg-emerald-600">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </div>
                        <span class="text-lg font-black tracking-tighter text-slate-900 uppercase">Rejestr <span class="text-emerald-600">Pracowników</span></span>
                    </a>

                    <!-- Main Navigation Links -->
                    <div class="hidden md:flex items-center gap-1">
                        @php
                            if ($isManagerOrAdmin) {
                                $navItems[] = ['route' => $homeRoute, 'label' => 'Dashboard', 'active' => request()->routeIs(['admin.dashboard', 'manager.dashboard', 'dashboard'])];
                            }
                            
                            $navItems = [
                                ['route' => 'biuletyn.index', 'label' => 'Biuletyn', 'active' => request()->routeIs('biuletyn.*')],
                                ['route' => 'company.show', 'label' => 'O firmie', 'active' => request()->routeIs('company.show')],
                            ];

                            if ($isManagerOrAdmin) {
                                $navItems[] = ['route' => 'admin.users.index', 'label' => 'Pracownicy', 'active' => request()->routeIs('admin.users.*')];
                                $navItems[] = ['route' => 'manager.contact.index', 'label' => 'Wiadomości', 'active' => request()->routeIs('manager.contact.*') || request()->routeIs('contact.*')];
                                $navItems[] = ['route' => 'manager.documents.index', 'label' => 'Dokumenty', 'active' => request()->routeIs('manager.documents.*') || request()->routeIs('documents.*')];
                                $navItems[] = ['route' => 'manager.work-logs.index', 'label' => 'Ewidencja Pracy', 'active' => request()->routeIs('manager.work-logs.*')];
                                $navItems[] = ['route' => 'manager.schedules.index', 'label' => 'Grafik', 'active' => request()->routeIs('manager.schedules.*')];
                            } else {
                                $navItems[] = ['route' => 'contact.index', 'label' => 'Poczta', 'active' => request()->routeIs('contact.*')];
                                $navItems[] = ['route' => 'documents.index', 'label' => 'Moje Pliki', 'active' => request()->routeIs('documents.*')];
                                $navItems[] = ['route' => 'user.work-logs.index', 'label' => 'Moja Praca', 'active' => request()->routeIs('user.work-logs.*')];
                            }
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ route($item['route']) }}" 
                               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $item['active'] ? 'text-emerald-600 bg-emerald-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Right Side Info -->
                <div class="flex items-center">
                    <div class="ml-4 pl-4 border-l border-slate-200 flex items-center gap-4">
                        <a href="{{ route($homeRoute) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs(['admin.dashboard', 'manager.dashboard', 'dashboard']) ? 'text-emerald-600 bg-emerald-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            Mój Profil
                        </a>
                        
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-bold text-slate-900 leading-none">{{ $user?->name }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $isAdmin ? 'Administrator' : ($isManager ? 'Kierownik' : 'Pracownik') }}</span>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Content Area -->
    <main class="py-12 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="max-w-7xl mx-auto mb-8">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl font-bold text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto mb-8">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl font-bold text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
