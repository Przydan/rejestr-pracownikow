@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('contact.index') }}" class="text-emerald-600 hover:text-emerald-700 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 mb-2 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Powrót do listy
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $thread->subject }}</h1>
            <div class="flex items-center gap-3 mt-1.5">
                <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest rounded-lg {{ $thread->status === 'open' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                    {{ $thread->status === 'open' ? 'Otwarty' : 'Zamknięty' }}
                </span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Wątek z administracją</span>
            </div>
        </div>
    </div>

    <!-- Conversation -->
    <div class="space-y-4">
        @foreach($thread->messages as $message)
            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[85%] lg:max-w-[75%] rounded-2xl p-4 {{ $message->user_id === auth()->id() ? 'bg-emerald-600 text-white rounded-tr-none shadow-md shadow-emerald-50' : 'bg-white border border-slate-200 text-slate-900 rounded-tl-none' }}">
                    <div class="flex justify-between items-baseline mb-1.5 gap-6">
                        <span class="text-[9px] font-bold uppercase tracking-widest {{ $message->user_id === auth()->id() ? 'text-emerald-100' : 'text-emerald-600' }}">
                            {{ $message->user_id === auth()->id() ? 'Ty' : 'Administracja' }}
                        </span>
                        <span class="text-[9px] font-medium {{ $message->user_id === auth()->id() ? 'text-emerald-200' : 'text-slate-400' }}">
                            {{ $message->created_at->format('d.m.Y H:i') }}
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
            <form action="{{ route('contact.reply', $thread) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="content" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Twoja wiadomość</label>
                    <textarea name="content" id="content" rows="3" required class="block w-full rounded-xl bg-slate-50 border-slate-100 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all text-sm font-medium p-4" placeholder="Wpisz treść..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="w-full sm:w-auto bg-emerald-600 text-white rounded-xl px-8 py-2.5 font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md">
                        Wyślij odpowiedź
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
@endsection
