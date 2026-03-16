<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="bg-black text-white text-center text-sm font-semibold py-2 px-4">
        Premium Women Collection • Elegant Styles • New Arrivals
    </div>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <button
                    type="button"
                    class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50"
                    @click="sidebarOpen = true"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-black text-white flex items-center justify-center font-extrabold text-lg">
                        A
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold tracking-wide leading-none">AREEJ</p>
                        <p class="text-xs text-gray-500 mt-1">Women Fashion Store</p>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex flex-1 max-w-2xl mx-6">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                    <input
                        type="text"
                        placeholder="Search for dresses, kurta sets, lehnga..."
                        class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                    >
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('users.contact') }}"
                    class="hidden sm:inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                    Contact
                </a>

                <a href="{{ route('users.wishlist') }}"
                    class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                <a href="{{ route('users.orders') }}"
                    class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9m-5-4l4 4m0 0l-4 4m4-4H9" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>