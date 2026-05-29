@extends('layouts.app')

@section('content')
<div x-data="{ 
    showPreviewModal: false, 
    previewUrl: '', 
    previewName: '' 
}" class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Moje Dokumenty</h1>
            <p class="text-slate-500 font-medium text-xs">Biblioteka dokumentów udostępnionych Tobie przez administrację.</p>
        </div>
        <a href="{{ route('contact.create', ['subject' => 'Zapytanie dotyczące dokumentacji']) }}" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
            Zgłoś sprawę / błąd
        </a>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($documents as $document)
            @php
                $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                $isPdf = strtolower($extension) === 'pdf';
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-emerald-400 transition-all group shadow-sm hover:shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 border border-slate-100">
                        @if($isPdf)
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <a href="{{ route('documents.download', $document) }}" class="p-2 bg-slate-50 text-slate-400 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Pobierz plik">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>

                <div class="mb-4">
                    <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ $document->category ?? 'Ogólne' }}</h4>
                    <h3 class="text-sm font-bold text-slate-900 line-clamp-1 mb-1">{{ $document->name }}</h3>
                    <p class="text-[10px] font-medium text-slate-500">Udostępniono: {{ $document->created_at?->format('d.m.Y') }}</p>
                </div>

                <div class="pt-3 border-t border-slate-50 flex justify-between items-center">
                    <span class="text-[9px] font-bold text-slate-400 uppercase">{{ strtoupper($extension) }}</span>
                    <button @click="previewUrl = '{{ Storage::url($document->file_path) }}'; previewName = '{{ $document->name }}'; showPreviewModal = true" 
                            class="text-emerald-600 font-bold text-[10px] uppercase tracking-widest hover:text-emerald-700 transition-colors">
                        Szybki Podgląd
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Brak dokumentów w Twojej bibliotece.</p>
            </div>
        @endforelse
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreviewModal" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;">
        <div @click.away="showPreviewModal = false; previewUrl = ''" class="bg-white w-full h-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-slate-900 px-6 py-3 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold" x-text="previewName"></h3>
                </div>
                <button @click="showPreviewModal = false; previewUrl = ''" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="flex-1 bg-slate-100">
                <template x-if="previewUrl">
                    <iframe :src="previewUrl" class="w-full h-full border-none"></iframe>
                </template>
            </div>
            <div class="p-3 border-t bg-white flex justify-between items-center px-6">
                <p class="text-[10px] font-medium text-slate-400 italic">Podgląd natywny dokumentu.</p>
                <a :href="previewUrl" download class="px-4 py-1.5 bg-slate-900 text-white rounded-lg font-bold text-[9px] uppercase tracking-widest hover:bg-emerald-600 transition-all">Pobierz plik</a>
            </div>
        </div>
    </div>
</div>
@endsection
