@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
            <p class="text-slate-600">Update the personal and professional details for {{ $user->name }}.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-white border border-slate-200 text-slate-700 px-6 py-2 rounded-full font-semibold text-sm transition-all hover:bg-slate-50">
            Cancel
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-8 max-w-3xl mx-auto">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="space-y-1">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                    @error('name') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                    @error('email') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" id="password" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Leave blank to keep current">
                    @error('password') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="employee_id" class="block text-sm font-semibold text-slate-700">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $user->employee_id) }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    @error('employee_id') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="department" class="block text-sm font-semibold text-slate-700">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $user->department) }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    @error('department') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="phone" class="block text-sm font-semibold text-slate-700">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    @error('phone') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="role_id" class="block text-sm font-semibold text-slate-700">User Role</label>
                    <select name="role_id" id="role_id" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()?->id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-1">
                    <label for="address" class="block text-sm font-semibold text-slate-700">Address</label>
                    <textarea name="address" id="address" rows="2" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">{{ old('address', $user->address) }}</textarea>
                    @error('address') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="notes" class="block text-sm font-semibold text-slate-700">Internal Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">{{ old('notes', $user->notes) }}</textarea>
                    @error('notes') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-6">
                    <div class="shrink-0">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Current Photo</label>
                        @if($user->photo_path)
                            <img src="{{ asset('storage/' . $user->photo_path) }}" alt="User photo" class="w-24 h-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                        @else
                            <div class="w-24 h-24 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-1">
                        <label for="photo" class="block text-sm font-semibold text-slate-700">Update Photo</label>
                        <div class="mt-1 flex justify-center px-6 py-4 border-2 border-slate-200 border-dashed rounded-xl hover:border-emerald-400 transition-colors group">
                            <div class="space-y-1 text-center">
                                <div class="flex text-sm text-slate-600">
                                    <label for="photo" class="relative cursor-pointer rounded-md font-semibold text-emerald-600 hover:text-emerald-500">
                                        <span>Upload new image</span>
                                        <input id="photo" name="photo" type="file" class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                        @error('photo') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-1 border-t border-slate-100 pt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-slate-900 font-bold">Konto aktywne</label>
                </div>
                <p class="mt-1 text-xs text-slate-500 italic">Odznacz, aby zablokować dostęp użytkownika do systemu.</p>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-100 mt-6">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-full font-bold text-sm transition-all shadow-sm">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
