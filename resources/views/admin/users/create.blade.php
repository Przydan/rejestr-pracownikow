@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create User</h1>
            <p class="text-slate-600">Enter the details to add a new employee to the registry.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-white border border-slate-200 text-slate-700 px-6 py-2 rounded-full font-semibold text-sm transition-all hover:bg-slate-50">
            Cancel
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-8 max-w-3xl mx-auto">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="space-y-1">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                    @error('name') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                    @error('email') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" id="password" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                    @error('password') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                </div>

                <div class="space-y-1">
                    <label for="employee_id" class="block text-sm font-semibold text-slate-700">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    @error('employee_id') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="department" class="block text-sm font-semibold text-slate-700">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department') }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    @error('department') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="phone" class="block text-sm font-semibold text-slate-700">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                    @error('phone') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="role_id" class="block text-sm font-semibold text-slate-700">User Role</label>
                    <select name="role_id" id="role_id" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-1">
                    <label for="address" class="block text-sm font-semibold text-slate-700">Address</label>
                    <textarea name="address" id="address" rows="2" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">{{ old('address') }}</textarea>
                    @error('address') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label for="notes" class="block text-sm font-semibold text-slate-700">Internal Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="block w-full px-4 py-2 rounded-xl border-slate-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-1">
                <label for="photo" class="block text-sm font-semibold text-slate-700">Profile Photo</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl hover:border-emerald-400 transition-colors group">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-emerald-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 8M12 8h12m-12 0a4 4 0 014-4h12a4 4 0 014 4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600">
                            <label for="photo" class="relative cursor-pointer rounded-md font-semibold text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="photo" name="photo" type="file" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('photo') <span class="text-rose-500 text-xs font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-100">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-full font-bold text-sm transition-all shadow-sm">
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
