@extends('admin.layouts.app')

@section('title', $isEdit ? 'Edit Admin User' : 'Add Admin User')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $isEdit ? 'Edit Admin User' : 'Add Admin User' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    {{ $isEdit ? 'Update admin account details' : 'Create a new admin account' }}
                </p>
            </div>

            <a href="{{ route('admin.admin-users.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                Back
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm p-8">
            <form action="{{ $isEdit ? route('admin.admin-users.update', $adminUser->id) : route('admin.admin-users.store') }}"
                method="POST" class="space-y-6">
                @csrf

                @if ($isEdit)
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $adminUser->name) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                        placeholder="Enter admin name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $adminUser->email) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                        placeholder="Enter admin email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isEdit ? 'New Password' : 'Password' }}
                        </label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="{{ $isEdit ? 'Leave blank to keep same' : 'Enter password' }}">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isEdit ? 'Confirm New Password' : 'Confirm Password' }}
                        </label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="Confirm password">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $adminUser->is_active ?? 1) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black">
                    <label for="is_active" class="text-sm text-gray-700">Active Admin</label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition">
                        {{ $isEdit ? 'Update Admin User' : 'Save Admin User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection