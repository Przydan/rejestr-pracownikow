@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div>
        <a href="{{ route('contact.index') }}" class="text-emerald-600 hover:underline text-xs font-bold uppercase tracking-widest flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Powrót do listy
        </a>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Nowy Wątek</h1>
        <p class="text-slate-500 font-medium mt-1">Skontaktuj się z administracją w sprawie swoich dokumentów lub innych potrzeb.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 p-8 lg:p-10">
        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="subject" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Temat wiadomości</label>
                <input type="text" name="subject" id="subject" required autofocus value="{{ old('subject') }}" 
                       class="block w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                       placeholder="Np. Prośba o aktualizację dokumentu">
                @error('subject')
                    <p class="mt-2 text-xs text-rose-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Treść zapytania</label>
                <textarea name="content" id="content" rows="6" required 
                          class="block w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 text-sm font-medium focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                          placeholder="Opisz szczegółowo swoją potrzebę...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-2 text-xs text-rose-600 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" 
                        class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-lg shadow-emerald-200 text-sm font-bold uppercase tracking-widest transition-all hover:scale-[1.01] active:scale-[0.99]">
                    Wyślij wiadomość do administracji
                </button>
            </div>
        </form>
    </div>

    <div class="bg-emerald-50 rounded-3xl p-6 border border-emerald-100 flex gap-4">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-emerald-800 uppercase tracking-widest mb-1">Wskazówka</p>
            <p class="text-sm text-emerald-700 font-medium leading-relaxed">Pamiętaj, aby w temacie krótko opisać sedno sprawy. Pomoże to nam szybciej przekazać Twoje zapytanie do odpowiedniej osoby.</p>
        </div>
    </div>
</div>
@endsection
