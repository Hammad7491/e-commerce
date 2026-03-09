@extends('admin.layouts.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="max-w-5xl mx-auto" x-data="{ tab: 'profile' }">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your account information and security settings.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 px-6 pt-6">
                <div class="flex flex-wrap gap-3">
                    <button
                        type="button"
                        @click="tab = 'profile'"
                        :class="tab === 'profile'
                            ? 'bg-black text-white shadow-sm'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
                    >
                        Change Profile
                    </button>

                    <button
                        type="button"
                        @click="tab = 'password'"
                        :class="tab === 'password'
                            ? 'bg-black text-white shadow-sm'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
                    >
                        Change Password
                    </button>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div x-show="tab === 'profile'" x-transition>
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div x-show="tab === 'password'" x-transition>
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection