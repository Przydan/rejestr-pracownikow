@extends('layouts.app')

@section('content')
<div x-data="{ showNewThreadModal: false, selectedUserId: '' }" class="max-w-7xl mx-auto space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                {{ $selectedUser ? "Wątki: {$selectedUser->name}" : 'Centrum Wiadomości' }}
            </h1>
            <p class="text-slate-500 font-medium text-sm">Zarządzaj korespondencją i odpowiadaj na zapytania pracowników.</p>
        </div>

        <div class="w-full md:w-auto flex items-center gap-3">
            <button @click="showNewThreadModal = true" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md shadow-emerald-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nowy Wątek
            </button>
        </div>
    </div>

    <!-- Toolbar: Filters & Search -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-col xl:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <!-- Status Tabs -->
            <div class="bg-slate-50 rounded-xl p-1 flex border border-slate-100">
                <a href="{{ request()->fullUrlWithQuery(['status' => 'open']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ request('status', 'open') === 'open' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">Otwarte</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'closed']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ request('status') === 'closed' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">Zamknięte</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ request('status') === 'all' ? 'bg-white text-slate-400 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">Wszystkie</a>
            </div>

            <!-- Sorting -->
            <div class="bg-slate-50 rounded-xl p-1 flex border border-slate-100">
                <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ !request('sort') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-400 hover:text-slate-800' }}">Aktywne</a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'waiting_longest']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ request('sort') === 'waiting_longest' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-400 hover:text-rose-500' }}">Oczekiwanie</a>
            </div>

            <a href="{{ request()->fullUrlWithQuery(['filter' => request('filter') === 'unread' ? null : 'unread']) }}" 
               class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ request('filter') === 'unread' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-500 hover:text-emerald-600 border border-slate-200' }}">
                Nieprzeczytane
            </a>
        </div>

        <div class="flex items-center gap-4 w-full xl:w-auto">
            <div class="relative flex-1 xl:w-80 group">
                <form action="{{ route('manager.contact.index') }}" method="GET" x-data @change.debounce.500ms="$el.submit()">
                    <input type="hidden" name="status" value="{{ request('status', 'open') }}">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Szukaj w treści..." 
                           class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2 pl-10 pr-4 text-xs font-medium transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>

            <form action="{{ route('manager.contact.mark-all-read') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" class="p-2 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm" title="Oznacz wszystko jako przeczytane">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Contact List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Pracownicy</h3>
                </div>
                
                <div class="p-2 space-y-1 overflow-y-auto max-h-[60vh] custom-scrollbar">
                    <a href="{{ route('manager.contact.index') }}" class="flex items-center gap-3 p-3 rounded-xl text-xs font-bold transition-all {{ !request('user_id') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 {{ !request('user_id') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Wszyscy
                    </a>
                    @foreach($users as $user)
                        <a href="{{ route('manager.contact.index', ['user_id' => $user->id]) }}" class="flex items-center gap-3 p-3 rounded-xl text-xs font-bold transition-all {{ request('user_id') == $user->id ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] {{ request('user_id') == $user->id ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <div class="leading-tight">{{ $user->name }}</div>
                                <div class="text-[9px] {{ request('user_id') == $user->id ? 'text-emerald-100' : 'text-slate-400' }} uppercase tracking-tighter">{{ $user->employee_id }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main: Thread List -->
        <div class="lg:col-span-3 space-y-4">
            @forelse($threads as $thread)
                @php
                    $lastMessage = $thread->messages->first();
                    $isUnread = $lastMessage && !$lastMessage->is_read && $lastMessage->user_id !== auth()->id();
                @endphp
                <div class="bg-white rounded-2xl border {{ $isUnread ? 'border-emerald-400 shadow-sm ring-1 ring-emerald-400' : 'border-slate-200' }} p-5 hover:border-emerald-600 transition-all group">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="relative shrink-0">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 font-bold text-sm">
                                {{ substr($thread->user->name, 0, 1) }}
                            </div>
                            @if($isUnread)
                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <div>
                                    <h4 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">{{ $thread->user->name }}</h4>
                                    <h3 class="text-sm font-bold text-slate-900 truncate">{{ $thread->subject }}</h3>
                                </div>
                                <span class="text-[10px] font-medium text-slate-400">{{ $thread->last_message_at?->diffForHumans() }}</span>
                            </div>
                            @if($lastMessage)
                                <p class="text-slate-500 text-xs font-medium line-clamp-1 leading-relaxed">
                                    {{ Str::limit($lastMessage->content, 100) }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-50">
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest rounded-full {{ $thread->status === 'open' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400' }}">
                                    {{ $thread->status === 'open' ? 'Otwarty' : 'Zamknięty' }}
                                </span>
                                <a href="{{ route('manager.contact.show', $thread) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-slate-900 text-white rounded-lg font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all">
                                    Otwórz
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-50 rounded-2xl p-12 text-center border-2 border-dashed border-slate-200">
                    <p class="text-slate-500 font-bold text-sm">Brak wątków do wyświetlenia.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- New Thread Modal -->
    <div x-show="showNewThreadModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showNewThreadModal = false" 
             class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
            <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold tracking-tight">Nowa Konwersacja</h3>
                <button @click="showNewThreadModal = false" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('manager.contact.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="user_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Odbiorca</label>
                        <select name="user_id" id="user_id" required class="block w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all">
                            <option value="">Wybierz...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label for="subject" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Temat</label>
                        <input type="text" name="subject" id="subject" required class="block w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all" placeholder="Temat rozmowy">
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="content" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Wiadomość</label>
                    <textarea name="content" id="content" rows="4" required class="block w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all" placeholder="Wpisz treść..."></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full sm:w-auto bg-emerald-600 text-white rounded-xl px-8 py-2.5 font-bold text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md">
                        Rozpocznij
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection
