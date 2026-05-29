@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ (auth()->user()->hasRole('administrator') || auth()->user()->hasRole('kierownik')) ? 'Witaj, ' . auth()->user()->name . '!' : 'Mój Profil' }}</h1>
            <p class="text-slate-500 font-medium text-xs">Oto podsumowanie Twojego profilu i najnowsze aktywności.</p>
        </div>
    </div>

    <!-- Stats / Shortcuts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('biuletyn.index') }}" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg">Biuletyn</h3>
            <p class="text-slate-500 text-sm mt-1">Sprawdź najnowsze ogłoszenia firmowe.</p>
        </a>

        <a href="{{ route('contact.index') }}" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-sky-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg">Wiadomości</h3>
            <p class="text-slate-500 text-sm mt-1">Twoja korespondencja z administracją.</p>
        </a>

        <a href="{{ route('documents.index') }}" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg">Dokumenty</h3>
            <p class="text-slate-500 text-sm mt-1">Biblioteka plików i umów.</p>
        </a>
    </div>

    <!-- Profile Details -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="md:flex">
            <div class="md:shrink-0 p-8 bg-slate-50 flex flex-col items-center justify-center border-r border-slate-100">
                @if(auth()->user()->photo_path)
                    <img class="h-40 w-40 object-cover rounded-full border-4 border-white shadow-md" src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-40 w-40 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shadow-inner">
                        <span class="text-5xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <h2 class="mt-4 text-xl font-bold text-slate-900">{{ auth()->user()->name }}</h2>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold mt-2 uppercase tracking-wider">
                    {{ auth()->user()->roles->first()?->name ?? 'Pracownik' }}
                </span>
            </div>
            <div class="p-8 flex-1">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Informacje o profilu</h3>
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-bold text-slate-500 uppercase">Adres Email</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ auth()->user()->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-500 uppercase">Numer Pracownika</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ auth()->user()->employee_id ?? '---' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-500 uppercase">Dział</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ auth()->user()->department ?? '---' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-500 uppercase">Telefon</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ auth()->user()->phone ?? '---' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold text-slate-500 uppercase">Adres</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ auth()->user()->address ?? '---' }}</dd>
                    </div>
                </div>

                @if(auth()->user()->notes)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase">Notatki / Dodatkowe informacje</dt>
                        <dd class="mt-1 text-sm text-slate-600 italic leading-relaxed">{{ auth()->user()->notes }}</dd>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden p-8">
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Mój Grafik Tygodniowy
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
            @foreach($schedules as $schedule)
                <div class="p-4 rounded-2xl {{ $schedule->is_working_day ? 'bg-slate-50 border border-slate-100' : 'bg-rose-50 border border-rose-100' }} transition-all hover:shadow-md">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::now()->startOfWeek($schedule->day_of_week)->translatedFormat('l') }}</p>
                    @if($schedule->is_working_day)
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-slate-900">{{ substr($schedule->start_time, 0, 5) }}</span>
                            <span class="text-xs font-bold text-slate-400">do</span>
                            <span class="text-sm font-black text-slate-900">{{ substr($schedule->end_time, 0, 5) }}</span>
                        </div>
                    @else
                        <span class="text-[10px] font-black text-rose-600 uppercase">Dzień wolny</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
