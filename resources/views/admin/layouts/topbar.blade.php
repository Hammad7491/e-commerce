<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Section -->
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-900">
                @if(request()->routeIs('admin.dashboard'))
                    Dashboard
                @elseif(request()->routeIs('admin.categories.*'))
                    Categories
                @elseif(request()->routeIs('admin.products*'))
                    Products
                @elseif(request()->routeIs('admin.settings*'))
                    Settings
                @elseif(request()->routeIs('profile.edit'))
                    Profile
                @else
                    Admin Panel
                @endif
            </h2>
        </div>

        <!-- Right Section (empty for now) -->
        <div class="flex items-center gap-4"></div>
    </div>
</header>