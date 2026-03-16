<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Section -->
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-900">
                @if(request()->routeIs('home'))
                    Home
                @elseif(request()->routeIs('dashboard'))
                    Dashboard
                @elseif(request()->routeIs('admin.categories.*'))
                    Categories
                @elseif(request()->routeIs('admin.products.*'))
                    Products
                @elseif(request()->routeIs('admin.admin-users.*'))
                    Admin Users
                @elseif(request()->routeIs('admin.notifications.*'))
                    Notifications
                @elseif(request()->routeIs('profile.edit'))
                    Profile Settings
                @else
                    Admin Panel
                @endif
            </h2>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            @auth
                <!-- Notifications -->
                <button type="button"
                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-3.22-3.22a9.16 9.16 0 01-1.27-3.8 6.16 6.16 0 01-2-11.6A6.16 6.16 0 0010.5 3.2a9.16 9.16 0 01-1.27 3.8L6 10h5m4 7v1a3 3 0 01-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-all focus:outline-none">
                        <div
                            class="w-9 h-9 bg-gradient-to-br from-gray-800 to-black rounded-lg flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>

                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 leading-tight">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <svg class="w-4 h-4 text-gray-500 transition-transform duration-200"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
                        style="display: none;">
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Guest / Public Home -->
                <a href="{{ route('home') }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-black transition">
                    Home
                </a>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-black text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                        Admin Login
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>