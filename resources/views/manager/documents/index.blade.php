@extends('layouts.app')

@section('content')
<div x-data="{ 
    showUploadModal: false, 
    showPreviewModal: false, 
    previewUrl: '', 
    previewName: '' 
}" class="max-w-7xl mx-auto space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                {{ $selectedUser ? "Biblioteka: {$selectedUser->name}" : 'Dokumenty' }}
            </h1>
            <p class="text-slate-500 font-medium text-sm">Zarządzaj biblioteką dokumentów i załączników pracowników.</p>
        </div>

        <div class="w-full md:w-auto flex items-center gap-3">
            <button @click="showUploadModal = true" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Dodaj Plik
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:w-80 group">
            <form action="{{ route('manager.documents.index') }}" method="GET" x-data @change.debounce.500ms="$el.submit()">
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Szukaj pracownika..." 
                       class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2 pl-10 pr-4 text-xs font-medium transition-all">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        <div class="flex items-center gap-4 text-xs font-bold text-slate-400">
            <span>Użycie dysku: <span class="text-slate-900">2.4 / 10 GB</span></span>
            <div class="w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="w-[24%] h-full bg-emerald-500"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pracownicy</h3>
                </div>
                <div class="p-2 space-y-1 overflow-y-auto max-h-[60vh] custom-scrollbar">
                    <a href="{{ route('manager.documents.index') }}" class="flex items-center gap-3 p-3 rounded-xl text-xs font-bold transition-all {{ !request('user_id') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 {{ !request('user_id') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Wszystkie
                    </a>
                    @foreach($users as $user)
                        <a href="{{ route('manager.documents.index', ['user_id' => $user->id]) }}" class="flex items-center gap-3 p-3 rounded-xl text-xs font-bold transition-all {{ request('user_id') == $user->id ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] {{ request('user_id') == $user->id ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <div class="leading-tight">{{ $user->name }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($documents as $document)
                    @php
                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                        $isPdf = strtolower($extension) === 'pdf';
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-emerald-400 transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 border border-slate-100">
                                @if($isPdf)
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <form action="{{ route('manager.documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Usuń dokument?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ $document->category ?? 'Ogólne' }}</h4>
                            <h3 class="text-sm font-bold text-slate-900 line-clamp-1 mb-1">{{ $document->name }}</h3>
                            <p class="text-[10px] font-medium text-slate-500">{{ $document->user->name }} &bull; {{ $document->created_at?->format('d.m.Y') }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">{{ strtoupper($extension) }}</span>
                            <button @click="previewUrl = '{{ Storage::url($document->file_path) }}'; previewName = '{{ $document->name }}'; showPreviewModal = true" 
                                    class="text-emerald-600 font-bold text-[10px] uppercase tracking-widest hover:text-emerald-700 transition-colors">
                                Podgląd
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                        <p class="text-slate-500 font-bold text-sm">Brak plików.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modals (Upload & Preview) -->
    <div x-show="showUploadModal" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showUploadModal = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="bg-slate-900 p-5 text-white flex justify-between items-center">
                <h3 class="font-bold">Nowy Dokument</h3>
                <button @click="showUploadModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('manager.documents.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div class="space-y-4">
                    <select name="user_id" required class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">
                        <option value="">Wybierz pracownika...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="name" required placeholder="Nazwa dokumentu" class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">
                    <input type="text" name="category" placeholder="Kategoria (opcjonalnie)" class="block w-full px-4 py-2 bg-slate-50 border-slate-200 rounded-xl text-sm font-medium">
                    <input type="file" name="document" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700">
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white rounded-xl py-2.5 font-bold text-xs uppercase tracking-widest shadow-md hover:bg-emerald-700 transition-all">Wgraj Plik</button>
            </form>
        </div>
    </div>

    <div x-show="showPreviewModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;">
        <div @click.away="showPreviewModal = false; previewUrl = ''" class="bg-white w-full h-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-slate-900 px-6 py-3 text-white flex justify-between items-center shrink-0">
                <h3 class="text-sm font-bold" x-text="previewName"></h3>
                <button @click="showPreviewModal = false; previewUrl = ''" class="text-slate-400 hover:text-white">&times;</button>
            </div>
            <div class="flex-1 bg-slate-100">
                <template x-if="previewUrl">
                    <iframe :src="previewUrl" class="w-full h-full border-none"></iframe>
                </template>
            </div>
            <div class="p-3 border-t flex justify-end px-6">
                <a :href="previewUrl" download class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-lg font-bold text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all">Pobierz</a>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
