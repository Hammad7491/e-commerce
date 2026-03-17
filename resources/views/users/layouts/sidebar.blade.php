<aside
    class="fixed lg:sticky top-0 left-0 z-50 lg:z-20 h-screen w-[240px] lg:w-[250px] bg-white border-r border-gray-200 transform transition-transform duration-300 lg:translate-x-0 shadow-sm"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="h-full flex flex-col">
        <div class="lg:hidden flex items-center justify-between px-4 py-4 border-b border-gray-200 bg-white">
            <div>
                <p class="text-base font-semibold tracking-wide text-gray-900">Browse</p>
                <p class="text-xs font-normal text-gray-500 mt-0.5">Women Fashion</p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition"
                @click="sidebarOpen = false"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-6" x-data="{ womenOpen: true }">
            <nav class="space-y-2">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2.5 rounded-xl text-[14px] font-medium tracking-[0.1px] transition duration-200
                    {{ request()->routeIs('home') ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                    All
                </a>

                <button
                    type="button"
                    @click="womenOpen = !womenOpen"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-[14px] font-medium tracking-[0.1px] transition duration-200"
                    :class="womenOpen ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-black'"
                >
                    <span>Women</span>
                    <svg class="w-4 h-4 transition-transform duration-200 text-gray-500"
                        :class="womenOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="womenOpen" x-transition class="pl-2 space-y-2" style="display: none;">
                    <a href="{{ route('users.clothing') }}"
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-[13px] font-medium tracking-[0.1px] transition duration-200
                        {{ request()->routeIs('users.clothing') ? 'bg-black text-white shadow-sm' : 'bg-gray-50 text-gray-900 hover:bg-gray-100' }}">
                        <span>Clothing</span>
                        <svg class="w-4 h-4 {{ request()->routeIs('users.clothing') ? 'text-white' : 'text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <a href="{{ route('users.orders') }}"
                    class="block px-3 py-2.5 rounded-xl text-[14px] font-medium tracking-[0.1px] transition duration-200
                    {{ request()->routeIs('users.orders') ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                    Orders
                </a>

                <a href="{{ route('users.wishlist') }}"
                    class="block px-3 py-2.5 rounded-xl text-[14px] font-medium tracking-[0.1px] transition duration-200
                    {{ request()->routeIs('users.wishlist') ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                    Wishlist
                </a>

                <a href="{{ route('users.contact') }}"
                    class="block px-3 py-2.5 rounded-xl text-[14px] font-medium tracking-[0.1px] transition duration-200
                    {{ request()->routeIs('users.contact') ? 'bg-gray-100 text-gray-900 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                    Contact Us
                </a>
            </nav>
        </div>
    </div>
</aside>