@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Panel Kierownika</h1>
        <p class="text-slate-600 mt-1">Zarządzanie pracownikami, komunikacją i dokumentacją.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('manager.contact.index') }}" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-sky-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg">Kontakt z pracownikami</h3>
            <p class="text-slate-500 text-sm mt-1">Odpowiadaj na zgłoszenia i zarządzaj wątkami.</p>
        </a>

        <a href="{{ route('manager.documents.index') }}" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg">Biblioteka Dokumentów</h3>
            <p class="text-slate-500 text-sm mt-1">Wgrywaj i zarządzaj dokumentami pracowników.</p>
        </a>

        <a href="{{ route('biuletyn.index') }}" class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-900 text-lg">Zarządzaj Biuletynem</h3>
            <p class="text-slate-500 text-sm mt-1">Dodawaj nowe wpisy i aktualności dla zespołu.</p>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Lista Pracowników</h2>
        </div>
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pracownik</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dział</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Akcje</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @if($user->photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover border border-slate-200" src="{{ asset('storage/' . $user->photo_path) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $user->department ?? '---' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-emerald-600 hover:text-emerald-900 font-bold">Zarządzaj &rarr;</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
