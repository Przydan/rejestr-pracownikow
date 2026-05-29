@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Biuletyn Wiadomości</h1>
            <p class="text-slate-500 font-medium text-xs">Najnowsze ogłoszenia i aktualności firmowe.</p>
        </div>
        @can('create', App\Models\Post::class)
            <a href="{{ route('biuletyn.create') }}" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md shadow-emerald-50">
                Nowy wpis
            </a>
        @endcan
    </div>

    <div class="grid gap-4">
        @forelse($posts as $post)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-emerald-400 transition-all group shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                    <div class="flex-1">
                        <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-bold uppercase tracking-widest mb-2">
                            {{ $post->category ?? 'Ogólne' }}
                        </span>
                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">
                            <a href="{{ route('biuletyn.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $post->created_at?->format('d.m.Y') }}</div>
                        <div class="text-[9px] text-slate-300 font-medium">{{ $post->created_at?->format('H:i') }}</div>
                    </div>
                </div>
                
                <p class="text-slate-500 text-sm leading-relaxed line-clamp-2 mb-4">
                    {{ Str::limit($post->content, 250) }}
                </p>

                <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 text-[10px] font-bold">
                            {{ substr($post->author->name, 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-600">{{ $post->author->name }}</span>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            @can('update', $post)
                                <a href="{{ route('biuletyn.edit', $post) }}" class="p-1.5 text-slate-300 hover:text-emerald-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            @endcan
                            @can('delete', $post)
                                <form action="{{ route('biuletyn.destroy', $post) }}" method="POST" onsubmit="return confirm('Usunąć wpis?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                        <a href="{{ route('biuletyn.show', $post) }}" class="text-emerald-600 font-bold text-[10px] uppercase tracking-widest hover:text-emerald-700">
                            Czytaj więcej &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Brak wpisów w biuletynie.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection
