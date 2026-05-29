@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Grafik Pracowników</h1>
            <p class="text-slate-500 font-medium text-sm">Definiuj standardowe godziny pracy dla pracowników.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Employee List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Wybierz pracownika</h3>
                </div>
                <div class="p-2 space-y-1 max-h-[60vh] overflow-y-auto">
                    @foreach($users as $user)
                        <a href="{{ route('manager.schedules.index', ['user_id' => $user->id]) }}" 
                           class="flex items-center gap-3 p-3 rounded-xl text-xs font-bold transition-all {{ request('user_id') == $user->id ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] {{ request('user_id') == $user->id ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="truncate">{{ $user->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            @if($selectedUser)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl font-black">
                            {{ substr($selectedUser->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900">{{ $selectedUser->name }}</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Definicja grafiku tygodniowego</p>
                        </div>
                    </div>

                    <form action="{{ route('manager.schedules.store') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                        
                        <div class="space-y-4">
                            @foreach($schedules as $day => $schedule)
                                <div class="flex items-center gap-6 p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                                    <div class="w-32">
                                        <p class="text-sm font-black text-slate-900">{{ \Carbon\Carbon::now()->startOfWeek($day)->translatedFormat('l') }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Dzień {{ $day }}</p>
                                    </div>

                                    <div class="flex-1 grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Początek</label>
                                            <input type="time" name="schedules[{{ $day }}][start_time]" value="{{ old("schedules.$day.start_time", substr($schedule->start_time, 0, 5)) }}" 
                                                   class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Koniec</label>
                                            <input type="time" name="schedules[{{ $day }}][end_time]" value="{{ old("schedules.$day.end_time", substr($schedule->end_time, 0, 5)) }}"
                                                   class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 transition-all">
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 px-6 border-l border-slate-200">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="schedules[{{ $day }}][is_working_day]" value="1" {{ old("schedules.$day.is_working_day", $schedule->is_working_day) ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                            <span class="ml-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-900 transition-colors">Pracujący</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-8 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="px-12 py-3 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg hover:shadow-emerald-200">
                                Zaktualizuj Grafik
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 py-32 text-center">
                    <div class="w-16 h-16 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-slate-400 font-bold text-sm">Wybierz pracownika z listy, aby zdefiniować jego grafik.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
