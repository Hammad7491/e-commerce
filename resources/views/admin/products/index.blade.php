@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    @php
        $totalProducts = $products->count();
        $activeProducts = $products->filter(fn($p) => (int) $p->total_stock > 0)->count();
        $outOfStockProducts = $products->filter(fn($p) => (int) $p->total_stock <= 0)->count();
        $categories = $products
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();
    @endphp

    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl gradient-primary flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">Products Management</h1>
                <p class="text-gray-600 mt-1">Manage your product inventory with advanced controls</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 justify-start md:justify-end">
            <button type="button" id="exportBtn"
                class="inline-flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 text-gray-800 rounded-xl hover:bg-gray-50 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3M4 6a2 2 0 012-2h12a2 2 0 012 2v3M6 20h12a2 2 0 002-2v-3" />
                </svg>
                <span class="text-sm font-semibold">Export</span>
            </button>

            <button type="button" id="refreshBtn"
                class="inline-flex items-center justify-center w-12 h-12 bg-white border border-gray-200 text-gray-800 rounded-xl hover:bg-gray-50 shadow-sm"
                title="Refresh">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v6h6M20 20v-6h-6M5 19a9 9 0 0014-7 9 9 0 00-9-9 9 9 0 00-7 3" />
                </svg>
            </button>

            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-2 px-5 py-3 gradient-primary text-white rounded-xl hover:opacity-95 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-sm font-semibold">Add Product</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">
        <div class="stat-card gradient-primary text-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/80 text-sm font-semibold">Total Products</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $totalProducts }}</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card gradient-success text-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/80 text-sm font-semibold">Active Products</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $activeProducts }}</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card gradient-danger text-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/80 text-sm font-semibold">Out of Stock</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $outOfStockProducts }}</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-gray-900">Products Data Table</h2>
                <p class="text-gray-600 text-sm mt-1">Comprehensive inventory management with advanced filtering and bulk actions</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-600">Quick Actions:</span>
                    <select id="bulkAction"
                        class="px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-800 text-sm font-semibold outline-none focus:border-gray-900">
                        <option value="">Select Action</option>
                        <option value="export">Export Selected</option>
                        <option value="delete">Delete Selected</option>
                        <option value="clear">Clear Selection</option>
                    </select>
                </div>

                <button type="button" id="applyBulkBtn"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:opacity-95">
                    Apply
                </button>
            </div>
        </div>

        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/40">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                <div class="lg:col-span-4">
                    <label class="block text-xs font-extrabold tracking-wider text-gray-600 uppercase mb-2">Search Products</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                            </svg>
                        </span>
                        <input id="searchInput" type="text"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-white outline-none focus:border-gray-900 text-sm"
                            placeholder="Search by name, SKU, or category...">
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <label class="block text-xs font-extrabold tracking-wider text-gray-600 uppercase mb-2">Category</label>
                    <select id="categoryFilter"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white outline-none focus:border-gray-900 text-sm font-semibold">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <label class="block text-xs font-extrabold tracking-wider text-gray-600 uppercase mb-2">Show Entries</label>
                    <select id="entriesSelect"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white outline-none focus:border-gray-900 text-sm font-semibold">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex flex-col md:flex-row md:items-center gap-3">
                <button type="button" id="applyFiltersBtn"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:opacity-95 w-full md:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Apply Filters
                </button>

                <button type="button" id="resetFiltersBtn"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white border border-gray-200 text-gray-800 font-semibold text-sm hover:bg-gray-50 w-full md:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v6h6M20 20v-6h-6M5 19a9 9 0 0014-7 9 9 0 00-9-9 9 9 0 00-7 3" />
                    </svg>
                    Reset
                </button>

                <div class="flex-1 bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-primary-100 text-primary-700 font-extrabold"
                            id="selectedCountBadge">0</span>
                        <span>items selected</span>
                    </div>
                    <button type="button" id="clearSelectionBtn" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-white border-b border-gray-100">
                    <tr class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">
                        <th class="px-6 py-4 w-12">
                            <input type="checkbox" id="checkAll" class="w-4 h-4 rounded border-gray-300">
                        </th>
                        <th class="px-6 py-4 text-left">ID</th>
                        <th class="px-6 py-4 text-left">Product</th>
                        <th class="px-6 py-4 text-left">SKU</th>
                        <th class="px-6 py-4 text-left">Category</th>
                        <th class="px-6 py-4 text-left">Price</th>
                        <th class="px-6 py-4 text-left">Stock</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTbody" class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        @php
                            $stock = (int) $product->total_stock;
                            $isInStock = $stock > 0;
                            $categoryId = $product->category?->id;
                            $categoryName = $product->category?->name ?? 'Uncategorized';
                            $sku = $product->product_code ?: '-';
                            $price = (float) $product->discounted_price;
                        @endphp
                        <tr class="hover:bg-gray-50"
                            data-id="{{ $product->id }}"
                            data-name="{{ strtolower($product->name) }}"
                            data-sku="{{ strtolower($sku) }}"
                            data-category-id="{{ $categoryId }}"
                            data-category-name="{{ strtolower($categoryName) }}"
                            data-status="{{ $isInStock ? 'in' : 'out' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="row-check w-4 h-4 rounded border-gray-300" value="{{ $product->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-xl bg-gray-100 text-gray-700 text-xs font-extrabold">
                                    #{{ $product->id }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 min-w-[260px]">
                                    @if ($product->firstMedia && $product->firstMedia->file_type === 'image')
                                        <img src="{{ asset('storage/' . $product->firstMedia->file_path) }}"
                                            alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200 bg-white">
                                    @elseif ($product->firstMedia && $product->firstMedia->file_type === 'video')
                                        <div class="w-12 h-12 rounded-xl border border-gray-200 bg-gray-100 flex items-center justify-center text-xs font-extrabold text-gray-600">
                                            VID
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center text-xs font-extrabold text-gray-400">
                                            N/A
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold text-gray-900 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                                            Added {{ $product->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-xl bg-primary-100 text-primary-700 text-xs font-extrabold">
                                    {{ $sku }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-xl bg-purple-100 text-purple-700 text-xs font-extrabold">
                                    {{ $categoryName }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-extrabold text-gray-900">PKR {{ number_format($price, 2) }}</div>
                                @if ((float) $product->actual_price > $price)
                                    <div class="text-xs text-gray-500 line-through">PKR {{ number_format((float) $product->actual_price, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-extrabold text-gray-900">{{ $stock }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $isInStock ? 'In stock' : 'Out of stock' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center hover:bg-blue-100"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center hover:bg-emerald-100"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        class="delete-btn w-9 h-9 rounded-xl bg-red-50 text-red-700 flex items-center justify-center hover:bg-red-100"
                                        data-id="{{ $product->id }}"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2 0V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-500">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-5 border-t border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p id="tableInfo" class="text-sm font-semibold text-gray-600">
                Showing <span class="text-gray-900 font-extrabold" id="showingCount">0</span> of
                <span class="text-gray-900 font-extrabold" id="totalCount">{{ $totalProducts }}</span> products
            </p>

            <div class="flex items-center gap-2">
                <button type="button" id="prevPageBtn"
                    class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-800 text-sm font-semibold hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Prev
                </button>
                <span class="text-sm font-semibold text-gray-600">
                    Page <span class="text-gray-900 font-extrabold" id="pageNumber">1</span>
                </span>
                <button type="button" id="nextPageBtn"
                    class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-800 text-sm font-semibold hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.products.bulkDestroy') }}" class="hidden">
        @csrf
        <input type="hidden" name="ids" id="bulkDeleteIds">
    </form>

    @push('scripts')
        <script>
            (function () {
                const tbody = document.getElementById('productsTbody');
                if (!tbody) return;

                const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
                const searchInput = document.getElementById('searchInput');
                const categoryFilter = document.getElementById('categoryFilter');
                const entriesSelect = document.getElementById('entriesSelect');
                const applyFiltersBtn = document.getElementById('applyFiltersBtn');
                const resetFiltersBtn = document.getElementById('resetFiltersBtn');

                const exportBtn = document.getElementById('exportBtn');
                const refreshBtn = document.getElementById('refreshBtn');

                const checkAll = document.getElementById('checkAll');
                const selectedCountBadge = document.getElementById('selectedCountBadge');
                const clearSelectionBtn = document.getElementById('clearSelectionBtn');
                const bulkAction = document.getElementById('bulkAction');
                const applyBulkBtn = document.getElementById('applyBulkBtn');

                const prevPageBtn = document.getElementById('prevPageBtn');
                const nextPageBtn = document.getElementById('nextPageBtn');
                const pageNumber = document.getElementById('pageNumber');
                const showingCount = document.getElementById('showingCount');
                const totalCount = document.getElementById('totalCount');

                let currentPage = 1;
                let filtered = rows.slice();

                function getPerPage() {
                    const v = parseInt(entriesSelect?.value || '10', 10);
                    return Number.isFinite(v) && v > 0 ? v : 10;
                }

                function rowMatches(row) {
                    const q = (searchInput?.value || '').trim().toLowerCase();
                    const cat = categoryFilter?.value || '';

                    const name = row.getAttribute('data-name') || '';
                    const sku = row.getAttribute('data-sku') || '';
                    const catName = row.getAttribute('data-category-name') || '';
                    const catId = row.getAttribute('data-category-id') || '';
                    const searchOk = !q || name.includes(q) || sku.includes(q) || catName.includes(q);
                    const catOk = !cat || catId === cat;

                    return searchOk && catOk;
                }

                function updateSelectionUI() {
                    const checks = Array.from(tbody.querySelectorAll('input.row-check'));
                    const selected = checks.filter(c => c.checked);
                    selectedCountBadge.textContent = String(selected.length);
                    checkAll.checked = selected.length > 0 && selected.length === checks.length;
                    checkAll.indeterminate = selected.length > 0 && selected.length < checks.length;
                }

                function applyFilters() {
                    filtered = rows.filter(rowMatches);
                    currentPage = 1;
                    render();
                }

                function resetFilters() {
                    if (searchInput) searchInput.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (categoryFilter) categoryFilter.value = '';
                    if (entriesSelect) entriesSelect.value = '10';
                    applyFilters();
                }

                function render() {
                    const perPage = getPerPage();
                    const total = filtered.length;
                    const totalPages = Math.max(1, Math.ceil(total / perPage));
                    currentPage = Math.min(currentPage, totalPages);

                    rows.forEach(r => r.classList.add('hidden'));
                    const start = (currentPage - 1) * perPage;
                    const end = start + perPage;
                    filtered.slice(start, end).forEach(r => r.classList.remove('hidden'));

                    if (pageNumber) pageNumber.textContent = String(currentPage);
                    if (showingCount) showingCount.textContent = String(Math.min(perPage, Math.max(0, total - start)));
                    if (totalCount) totalCount.textContent = String({{ $totalProducts }});

                    if (prevPageBtn) prevPageBtn.disabled = currentPage <= 1;
                    if (nextPageBtn) nextPageBtn.disabled = currentPage >= totalPages;

                    updateSelectionUI();
                }

                function getVisibleRows() {
                    return filtered.filter(r => !r.classList.contains('hidden'));
                }

                function toCsvCell(value) {
                    const str = String(value ?? '');
                    if (/[",\n]/.test(str)) return '"' + str.replace(/"/g, '""') + '"';
                    return str;
                }

                function downloadCsv(filename, rowsData) {
                    const csv = rowsData.map(r => r.map(toCsvCell).join(',')).join('\n');
                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);
                }

                function exportRows(rowsToExport) {
                    const header = ['ID', 'Name', 'SKU', 'Category', 'Price', 'Stock', 'Status'];
                    const data = rowsToExport.map(row => {
                        const id = row.getAttribute('data-id') || '';
                        const name = row.querySelector('td:nth-child(3) p')?.textContent?.trim() || '';
                        const sku = row.getAttribute('data-sku') || '';
                        const category = row.querySelector('td:nth-child(5) span')?.textContent?.trim() || '';
                        const price = row.querySelector('td:nth-child(6) .text-sm')?.textContent?.replace('PKR', '')?.trim() || '';
                        const stock = row.querySelector('td:nth-child(7) .text-sm')?.textContent?.trim() || '';
                        const status = row.getAttribute('data-status') === 'in' ? 'In Stock' : 'Out of Stock';
                        return [id, name, sku, category, price, stock, status];
                    });

                    downloadCsv('products-export.csv', [header, ...data]);
                }

                function getSelectedRows() {
                    const ids = new Set(Array.from(tbody.querySelectorAll('input.row-check:checked')).map(i => i.value));
                    return rows.filter(r => ids.has(r.getAttribute('data-id')));
                }

                function clearSelection() {
                    tbody.querySelectorAll('input.row-check').forEach(i => i.checked = false);
                    if (checkAll) {
                        checkAll.checked = false;
                        checkAll.indeterminate = false;
                    }
                    updateSelectionUI();
                }

                applyFiltersBtn?.addEventListener('click', applyFilters);
                resetFiltersBtn?.addEventListener('click', resetFilters);
                entriesSelect?.addEventListener('change', () => { currentPage = 1; render(); });

                searchInput?.addEventListener('input', () => {
                    applyFilters();
                });

                exportBtn?.addEventListener('click', () => exportRows(getVisibleRows()));
                refreshBtn?.addEventListener('click', () => window.location.reload());

                checkAll?.addEventListener('change', (e) => {
                    const checked = e.target.checked;
                    tbody.querySelectorAll('input.row-check').forEach(i => { i.checked = checked; });
                    updateSelectionUI();
                });

                tbody.addEventListener('change', (e) => {
                    if (e.target && e.target.classList.contains('row-check')) updateSelectionUI();
                });

                clearSelectionBtn?.addEventListener('click', clearSelection);

                applyBulkBtn?.addEventListener('click', () => {
                    const action = bulkAction?.value || '';
                    const selectedRows = getSelectedRows();

                    if (!action) return;
                    if (action === 'clear') return clearSelection();
                    if (action === 'export') return exportRows(selectedRows);
                    if (action === 'delete') {
                        if (!selectedRows.length) return;
                        if (!window.confirm('Are you sure you want to delete selected products?')) return;

                        const ids = selectedRows.map(r => r.getAttribute('data-id'));
                        const bulkForm = document.getElementById('bulkDeleteForm');
                        const hidden = document.getElementById('bulkDeleteIds');
                        hidden.value = JSON.stringify(ids);
                        bulkForm.submit();
                        return;
                    }
                });

                tbody.addEventListener('click', (e) => {
                    const btn = e.target.closest('.delete-btn');
                    if (!btn) return;
                    const id = btn.getAttribute('data-id');
                    if (!id) return;
                    if (!window.confirm('Are you sure you want to delete this product?')) return;

                    const form = document.getElementById('deleteForm');
                    form.action = "{{ url('admin/products') }}/" + id;
                    form.submit();
                });

                prevPageBtn?.addEventListener('click', () => {
                    currentPage = Math.max(1, currentPage - 1);
                    render();
                });

                nextPageBtn?.addEventListener('click', () => {
                    currentPage = currentPage + 1;
                    render();
                });

                applyFilters();
            })();
        </script>
    @endpush
@endsection