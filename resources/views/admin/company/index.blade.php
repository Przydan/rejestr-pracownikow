@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">O firmie</h1>
            <p class="text-slate-500 font-medium text-sm">Zarządzaj informacjami o organizacji.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8">
            <form action="{{ route('admin.company.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nazwa Firmy</label>
                        <input type="text" name="name" value="{{ old('name', $info->name) }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">
                    </div>

                    <div class="col-span-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Adres</label>
                        <input type="text" name="address" value="{{ old('address', $info->address) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $info->nip) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">REGON</label>
                        <input type="text" name="regon" value="{{ old('regon', $info->regon) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email kontaktowy</label>
                        <input type="email" name="email" value="{{ old('email', $info->email) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Telefon</label>
                        <input type="text" name="phone" value="{{ old('phone', $info->phone) }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">
                    </div>

                    <div class="col-span-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Opis / O nas</label>
                        <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 py-2.5 px-4 text-sm font-medium transition-all">{{ old('description', $info->description) }}</textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-md">
                        Zapisz zmiany
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
