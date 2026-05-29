@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <a href="{{ route('biuletyn.index') }}" class="text-emerald-600 hover:text-emerald-700 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Wróć do listy
            </a>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-bold uppercase tracking-widest">
                    {{ $post->category ?? 'Ogólne' }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    {{ $post->created_at?->format('d.m.Y H:i') }}
                </span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $post->title }}</h1>
        </div>
        
        <div class="flex items-center gap-2">
            @can('update', $post)
                <a href="{{ route('biuletyn.edit', $post) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                    Edytuj
                </a>
            @endcan
            @can('delete', $post)
                <form action="{{ route('biuletyn.destroy', $post) }}" method="POST" onsubmit="return confirm('Usunąć wpis?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-rose-100 transition-all">
                        Usuń
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-wrap text-sm">
            {{ $post->content }}
        </div>
        
        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center gap-4">
            <div class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 text-sm font-bold">
                {{ substr($post->author->name, 0, 1) }}
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900 leading-tight">{{ $post->author->name }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Autor wpisu</p>
            </div>
        </div>
    </div>
</div>
@endsection
