@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('biuletyn.show', $post) }}" class="text-emerald-600 hover:underline text-sm font-semibold">&larr; Powrót do wpisu</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Edytuj wpis: {{ $post->title }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('biuletyn.update', $post) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Tytuł</label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required class="block w-full rounded-full border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm px-4 py-2">
                @error('title')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Kategoria</label>
                <input type="text" name="category" id="category" value="{{ old('category', $post->category) }}" placeholder="np. Ogłoszenia, Eventy" class="block w-full rounded-full border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm px-4 py-2">
                @error('category')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Treść</label>
                <textarea name="content" id="content" rows="10" required class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-4">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-emerald-600 text-white rounded-full px-6 py-2 font-semibold text-sm hover:bg-emerald-700 transition-all shadow-sm">
                    Zapisz zmiany
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
