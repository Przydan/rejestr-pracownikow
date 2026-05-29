@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar: Employee List -->
    <div class="hidden lg:block w-72 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 sticky top-24 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Pracownicy</h3>
            </div>
            <div class="p-2 space-y-1 overflow-y-auto max-h-[70vh] custom-scrollbar">
                @foreach($users as $user)
                    <a href="{{ route('manager.contact.index', ['user_id' => $user->id]) }}" class="flex items-center gap-3 p-3 rounded-xl text-xs font-bold transition-all {{ $thread->user_id == $user->id ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] {{ $thread->user_id == $user->id ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <div class="leading-tight">{{ $user->name }}</div>
                            <div class="text-[9px] {{ $thread->user_id == $user->id ? 'text-emerald-100' : 'text-slate-400' }} uppercase tracking-tighter">{{ $user->employee_id }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('manager.contact.index') }}" class="text-emerald-600 hover:text-emerald-700 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 mb-2 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Wróć do listy
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $thread->subject }}</h1>
                <div class="flex items-center gap-3 mt-1.5">
                    <span class="text-xs font-bold text-slate-500">{{ $thread->user->name }}</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest rounded-lg {{ $thread->status === 'open' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $thread->status === 'open' ? 'Otwarty' : 'Zamknięty' }}
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @if($thread->status === 'closed' && auth()->user()->hasRole('administrator'))
                    <form action="{{ route('manager.contact.open', $thread) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                            Otwórz ponownie
                        </button>
                    </form>
                @endif
                @if($thread->status === 'open')
                    <form action="{{ route('manager.contact.close', $thread) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                            Zamknij wątek
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Conversation -->
        <div class="space-y-4">
            @foreach($thread->messages as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%] lg:max-w-[75%] rounded-2xl p-4 {{ $message->user_id === auth()->id() ? 'bg-emerald-600 text-white rounded-tr-none shadow-md shadow-emerald-50' : 'bg-white border border-slate-200 text-slate-900 rounded-tl-none' }}">
                        <div class="flex justify-between items-baseline mb-1.5 gap-6">
                            <span class="text-[9px] font-bold uppercase tracking-widest {{ $message->user_id === auth()->id() ? 'text-emerald-100' : 'text-emerald-600' }}">
                                {{ $message->user_id === auth()->id() ? 'Ty' : $thread->user->name }}
                            </span>
                            <span class="text-[9px] font-medium {{ $message->user_id === auth()->id() ? 'text-emerald-200' : 'text-slate-400' }}">
                                {{ $message->created_at?->format('d.m.Y H:i') }}
                            </span>
                        </div>
                        <div class="text-sm font-medium leading-relaxed whitespace-pre-wrap">{{ $message->content }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Area -->
        @if($thread->status === 'open')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <form action="{{ route('manager.contact.reply', $thread) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="content" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Twoja odpowiedź</label>
                        <textarea name="content" id="content" rows="3" required class="block w-full rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all text-sm font-medium p-4" placeholder="Wpisz treść..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-emerald-600 text-white rounded-xl px-8 py-2.5 font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md">
                            Wyślij wiadomość
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-slate-100 rounded-2xl p-6 text-center border border-slate-200">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Wątek Zamknięty</p>
            </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
