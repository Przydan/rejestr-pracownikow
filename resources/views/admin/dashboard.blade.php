@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Panel Administratora</h1>
        <p class="text-slate-600">Przegląd statystyk systemu i szybki dostęp do zarządzania.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-emerald-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-emerald-700">Wszyscy Użytkownicy</p>
                <p class="text-2xl font-bold text-emerald-900">{{ $total_users }}</p>
            </div>
        </div>

        <!-- Administrators -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-emerald-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-emerald-700">Administratorzy</p>
                <p class="text-2xl font-bold text-emerald-900">{{ $admin_count }}</p>
            </div>
        </div>

        <!-- Managers -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-emerald-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-emerald-700">Kierownicy</p>
                <p class="text-2xl font-bold text-emerald-900">{{ $manager_count }}</p>
            </div>
        </div>

        <!-- Employees -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-emerald-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-emerald-700">Pracownicy</p>
                <p class="text-2xl font-bold text-emerald-900">{{ $employee_count }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Placeholder for Activity/Metrics) -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Aktywność Systemu</h2>
                    <p class="text-sm text-slate-600">Ostatnie zdarzenia w rejestrze.</p>
                </div>
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="p-4 bg-slate-50 rounded-full text-slate-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-slate-500">Brak ostatnich aktywności do wyświetlenia.</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Szybkie Akcje</h2>
                </div>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center px-4 py-3 bg-emerald-600 text-white rounded-full font-semibold text-sm hover:bg-emerald-700 transition-all shadow-sm">
                        Dodaj nowego pracownika
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-full font-semibold text-sm hover:bg-slate-50 transition-all">
                        Zarządzaj użytkownikami
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
