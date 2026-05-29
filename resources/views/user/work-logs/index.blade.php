@extends('layouts.app')

@section('content')
<div x-data="{ showAddLogModal: false, selectedDate: '{{ date('Y-m-d') }}' }" class="max-w-5xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Moja Historia Pracy</h1>
            <p class="text-slate-500 font-medium text-sm">Przeglądaj przepracowane godziny i komentarze.</p>
        </div>

        <div class="flex items-center gap-4 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
            <button @click="showAddLogModal = true" class="flex items-center gap-2 px-6 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-md mr-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Dodaj Godziny
            </button>
            <form action="{{ route('user.work-logs.index') }}" method="GET" class="flex items-center gap-3">
                <select name="month" onchange="this.form.submit()" class="rounded-xl border-transparent bg-slate-50 text-xs font-bold focus:ring-0">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="rounded-xl border-transparent bg-slate-50 text-xs font-bold focus:ring-0">
                    @foreach(range(date('Y')-1, date('Y')+1) as $y)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Monthly Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Suma godzin</p>
            <p class="text-3xl font-black text-emerald-600">{{ $workLogs->sum('hours') }}h</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Dni robocze</p>
            <p class="text-3xl font-black text-slate-900">{{ $workLogs->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Średnia dziennie</p>
            <p class="text-3xl font-black text-slate-900">{{ $workLogs->count() > 0 ? number_format($workLogs->sum('hours') / $workLogs->count(), 1) : 0 }}h</p>
        </div>
    </div>

    <!-- Timeline List -->
    <div class="space-y-4">
        @forelse($workLogs as $log)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:border-emerald-200 transition-all">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-48 bg-slate-50/50 p-6 flex flex-col justify-center border-b md:border-b-0 md:border-r border-slate-100">
                        <p class="text-2xl font-black text-slate-900">{{ $log->date->format('d') }}</p>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ $log->date->translatedFormat('F Y') }}</p>
                        <p class="text-[10px] font-bold text-emerald-600 uppercase mt-1">{{ $log->date->translatedFormat('l') }}</p>
                    </div>

                    <div class="flex-1 p-6 flex flex-col md:flex-row justify-between gap-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-black rounded-lg">{{ number_format($log->hours, 1) }}h</span>
                                    @if($log->start_time)
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ substr($log->start_time, 0, 5) }} - {{ substr($log->end_time, 0, 5) }}</span>
                                    @endif
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Zatwierdził: <span class="text-slate-900">{{ $log->supervisor->name }}</span></span>
                                </div>
                                @if($log->description)
                                    <p class="text-sm text-slate-600 font-medium italic">"{{ $log->description }}"</p>
                                @endif
                            </div>

                            <!-- Comments Section -->
                            <div class="space-y-3 pt-4 border-t border-slate-50">
                                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Komentarze</h4>
                                <div class="space-y-2">
                                    @foreach($log->comments as $comment)
                                        <div class="flex gap-3 items-start">
                                            <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-[9px] font-black text-slate-500 shrink-0">
                                                {{ substr($comment->user->name, 0, 1) }}
                                            </div>
                                            <div class="bg-slate-50 rounded-xl p-3 flex-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-[10px] font-bold text-slate-900">{{ $comment->user->name }}</span>
                                                    <span class="text-[8px] font-bold text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-slate-600 font-medium">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                    <form action="{{ route('user.work-logs.comment', $log) }}" method="POST" class="flex gap-3 items-center mt-4">
                                        @csrf
                                        <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center text-[9px] font-black text-emerald-600 shrink-0">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <input type="text" name="content" required placeholder="Napisz komentarz..." class="flex-1 rounded-xl border-slate-200 bg-slate-50 focus:bg-white text-xs font-medium py-2 px-4 transition-all focus:ring-2 focus:ring-emerald-500">
                                        <button type="submit" class="bg-slate-900 text-white p-2 rounded-xl hover:bg-emerald-600 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 py-16 text-center">
                <p class="text-slate-400 font-bold text-sm">Brak wpisów w wybranym miesiącu.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal: Add Log -->
    <div x-show="showAddLogModal" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showAddLogModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
                <h3 class="font-bold">Dodaj Godziny Pracy</h3>
                <button @click="showAddLogModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('user.work-logs.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data</label>
                        <input type="date" name="date" x-model="selectedDate" max="{{ date('Y-m-d') }}" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Godzina rozpoczęcia</label>
                            <input type="time" name="start_time" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Godzina zakończenia</label>
                            <input type="time" name="end_time" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Opis wykonywanych prac (opcjonalnie)</label>
                        <textarea name="description" rows="3" placeholder="Co dziś zrobiłeś?" class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium"></textarea>
                    </div>
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white rounded-xl py-2.5 font-bold text-xs uppercase tracking-widest shadow-md hover:bg-emerald-700 transition-all">Dodaj wpis</button>
                <p class="text-[10px] text-center text-slate-400 font-medium">Uwaga: Wpisów nie można samodzielnie usuwać ani edytować po zapisaniu.</p>
            </form>
        </div>
    </div>
</div>
@endsection
