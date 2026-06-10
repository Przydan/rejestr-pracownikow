@extends('layouts.app')

@section('content')
<div x-data="{ showAddLogModal: {{ $errors->any() ? 'true' : 'false' }}, selectedDate: '{{ old('date', date('Y-m-d')) }}' }" class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Ewidencja Czasu Pracy</h1>
            <p class="text-slate-500 font-medium text-sm">Zarządzaj godzinami przepracowanymi przez pracowników.</p>
        </div>

        @if($selectedUser)
        <button @click="showAddLogModal = true" class="flex items-center gap-2 px-6 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Dodaj Godziny
        </button>
        @endif
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
                        <a href="{{ route('manager.work-logs.index', ['user_id' => $user->id]) }}"
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
        <div class="lg:col-span-3 space-y-6">
            @if($selectedUser)
                <!-- Month Filter -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <form action="{{ route('manager.work-logs.index') }}" method="GET" class="flex items-center gap-4">
                        <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                        <select name="month" onchange="this.form.submit()" class="rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" onchange="this.form.submit()" class="rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                            @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>

                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Suma godzin</p>
                        <p class="text-xl font-black text-emerald-600">{{ $workLogs->sum('hours') }}h</p>
                    </div>
                </div>

                <!-- History Table -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Data</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Godziny</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dodał</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Komentarze</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($workLogs as $log)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-slate-900">{{ $log->date->format('d.m.Y') }}</p>
                                        <p class="text-[10px] font-medium text-slate-500 uppercase">{{ $log->date->translatedFormat('l') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                            {{ number_format($log->hours, 1) }}h
                                        </span>
                                        @if($log->start_time)
                                            <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">{{ substr($log->start_time, 0, 5) }} - {{ substr($log->end_time, 0, 5) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-xs font-bold text-slate-700">{{ $log->supervisor->name }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            @foreach($log->comments as $comment)
                                                <div class="text-[10px] leading-snug">
                                                    <span class="font-bold text-slate-900">{{ $comment->user->name }}:</span>
                                                    <span class="text-slate-500">{{ $comment->content }}</span>
                                                </div>
                                            @endforeach
                                            <form action="{{ route('manager.work-logs.comment', $log) }}" method="POST" class="mt-2 flex gap-2">
                                                @csrf
                                                <input type="text" name="content" required placeholder="Dodaj komentarz..." class="flex-1 rounded-lg border-slate-200 text-[10px] py-1 bg-slate-50 focus:bg-white transition-all">
                                                <button type="submit" class="p-1 text-emerald-600 hover:text-emerald-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="selectedDate = '{{ $log->date->format('Y-m-d') }}'; showAddLogModal = true" class="text-slate-400 hover:text-emerald-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center bg-slate-50/50">
                                        <p class="text-slate-500 font-bold text-sm">Brak wpisów w tym miesiącu.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 py-24 text-center">
                    <p class="text-slate-400 font-bold text-sm">Wybierz pracownika z listy, aby zarządzać jego godzinami.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal: Add/Edit Log -->
    <div x-show="showAddLogModal" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showAddLogModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
                <h3 class="font-bold">Zapisz Godziny</h3>
                <button @click="showAddLogModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('manager.work-logs.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="user_id" value="{{ $selectedUser?->id }}">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
                        <strong class="font-bold">Wystąpił błąd!</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data</label>
                        <input type="date" name="date" x-model="selectedDate" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium @error('date') border-red-500 @enderror" value="{{ old('date') }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Godzina rozpoczęcia</label>
                            <input type="time" name="start_time" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium @error('start_time') border-red-500 @enderror" value="{{ old('start_time') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Godzina zakończenia</label>
                            <input type="time" name="end_time" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium @error('end_time') border-red-500 @enderror" value="{{ old('end_time') }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Opis / Zadania (opcjonalnie)</label>
                        <textarea name="description" rows="3" class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white rounded-xl py-2.5 font-bold text-xs uppercase tracking-widest shadow-md hover:bg-emerald-700 transition-all">Zapisz</button>
            </form>
        </div>
    </div>
</div>
@endsection
