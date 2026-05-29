@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">O firmie</h1>
            <p class="text-slate-500 font-medium text-sm">Informacje o naszej organizacji.</p>
        </div>
        @if(auth()->user()->hasRole('administrator'))
            <a href="{{ route('admin.company.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                Edytuj Dane
            </a>
        @endif
    </div>

    @if($info)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <h2 class="text-2xl font-black text-slate-900 mb-6">{{ $info->name }}</h2>
                    <div class="prose prose-slate max-w-none text-slate-600 font-medium leading-relaxed">
                        {!! nl2br(e($info->description)) !!}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3">Dane Kontaktowe</h3>
                    
                    <div class="space-y-4">
                        @if($info->address)
                            <div class="flex gap-4 items-start">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Adres</p>
                                    <p class="text-sm font-bold text-slate-900">{{ $info->address }}</p>
                                </div>
                            </div>
                        @endif

                        @if($info->email)
                            <div class="flex gap-4 items-start">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Email</p>
                                    <p class="text-sm font-bold text-slate-900">{{ $info->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if($info->phone)
                            <div class="flex gap-4 items-start">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Telefon</p>
                                    <p class="text-sm font-bold text-slate-900">{{ $info->phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3">Identyfikacja</h3>
                    <div class="space-y-4">
                        @if($info->nip)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">NIP</p>
                                <p class="text-sm font-bold text-slate-900">{{ $info->nip }}</p>
                            </div>
                        @endif
                        @if($info->regon)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">REGON</p>
                                <p class="text-sm font-bold text-slate-900">{{ $info->regon }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 py-24 text-center">
            <p class="text-slate-500 font-bold text-sm">Informacje o firmie nie zostały jeszcze uzupełnione.</p>
        </div>
    @endif
</div>
@endsection
