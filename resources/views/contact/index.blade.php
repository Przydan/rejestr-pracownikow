@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Moje Wiadomości</h1>
            <p class="text-slate-500 font-medium text-xs">Historia korespondencji z administracją.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('contact.create') }}" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md shadow-emerald-50">
                Nowa wiadomość
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[9px] font-bold text-slate-400 uppercase tracking-widest">Temat</th>
                        <th class="px-6 py-3 text-left text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-3 text-left text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ostatnia zmiana</th>
                        <th class="px-6 py-3 text-right text-[9px] font-bold text-slate-400 uppercase tracking-widest">Akcje</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($threads as $thread)
                        @php
                            $lastMessage = $thread->messages()->latest()->first();
                            $isUnread = $lastMessage && !$lastMessage->is_read && $lastMessage->user_id !== auth()->id();
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($isUnread)
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                                    @endif
                                    <div class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $thread->subject }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest rounded-lg {{ $thread->status === 'open' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $thread->status === 'open' ? 'Otwarty' : 'Zamknięty' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-900">{{ $thread->updated_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('contact.show', $thread) }}" class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-600 rounded-lg font-bold text-[9px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">
                                    Otwórz &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Brak wiadomości</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
