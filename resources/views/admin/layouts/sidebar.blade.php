<div class="w-64 h-screen flex flex-col bg-gray-50 rounded-r-3xl relative"
    style="box-shadow: 8px 0 20px rgba(0, 0, 0, 0.08), 12px 0 35px rgba(0, 0, 0, 0.06), 16px 0 50px rgba(0, 0, 0, 0.04); z-index: 50;">

    <div class="px-6 py-6 bg-white">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-black rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Boutique</h1>
                <p class="text-xs text-gray-500">Admin Portal</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-4">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-black text-white' : 'text-gray-700 hover:bg-white' }} transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.admin-users.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.admin-users.*') ? 'bg-black text-white' : 'text-gray-700 hover:bg-white' }} transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-medium">Admin Users</span>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-black text-white' : 'text-gray-700 hover:bg-white' }} transition-all duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium">Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="border-t border-gray-100 p-4">
        <div class="flex items-center space-x-3 px-3 py-3 mb-3 bg-gray-50 rounded-xl">
            <div class="w-11 h-11 bg-gradient-to-br from-gray-800 to-black rounded-xl flex items-center justify-center shadow-sm">
                <span class="text-white font-bold text-base">
                    {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">
                    {{ auth()->user()->name ?? 'Super Admin' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ auth()->user()->email ?? 'admin@boutique.com' }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center space-x-2 px-4 py-3 border-2 border-gray-200 rounded-xl text-gray-700 hover:bg-gray-900 hover:border-gray-800 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-sm font-semibold">Logout</span>
            </button>
        </form>
    </div>
</div>