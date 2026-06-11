@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Zarządzanie użytkownikami</h1>
            <p class="text-slate-600">Zarządzaj kontami pracowników, rolami i danymi osobowymi.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-full font-semibold text-sm transition-all shadow-sm">
            Dodaj użytkownika
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj pracowników..." class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-full text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-full text-sm font-bold hover:bg-emerald-600 transition-all shadow-sm">
                    Szukaj
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nazwa</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Pracownika</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dział</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Akcje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                    @if($user->photo_path)
                                        <img src="{{ asset('storage/' . $user->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs font-bold text-slate-500">{{ substr($user->name, 0, 1) }}</div>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-slate-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->employee_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->department }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-3">
                             <a href="{{ route('admin.users.edit', $user->id) }}" class="text-emerald-600 hover:text-emerald-800 font-semibold transition-colors">Edytuj</a>
                             @if(auth()->user()->hasRole('administrator'))
                                 <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold transition-colors" onclick="return confirm('Jesteś pewien?')">Usuń</button>
                                 </form>
                             @endif
                         </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
