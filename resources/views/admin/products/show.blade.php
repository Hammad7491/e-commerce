@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
    @php
        $stock = (int) $product->total_stock;
        $isInStock = $stock > 0;
        $media = $product->media ?? collect();
        $images = $media->where('file_type', 'image')->values();
        $videos = $media->where('file_type', 'video')->values();
        $cover = $images->first();
        $categoryName = $product->category?->name ?? 'Uncategorized';
        $sku = $product->product_code ?: '-';
        $actualPrice = (float) $product->actual_price;
        $discountedPrice = (float) $product->discounted_price;
    @endphp

    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl gradient-primary flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
            </div>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight truncate">{{ $product->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-xl bg-gray-100 text-gray-700 text-xs font-extrabold">
                        #{{ $product->id }}
                    </span>
                </div>
                <p class="text-gray-600 mt-1">Professional overview, media preview, and complete product specifications</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 text-gray-800 rounded-xl hover:bg-gray-50 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-sm font-semibold">Back</span>
            </a>

            <a href="{{ route('admin.products.edit', $product) }}"
                class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 text-gray-800 rounded-xl hover:bg-gray-50 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="text-sm font-semibold">Edit</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Media Gallery</h2>
                        <p class="text-sm text-gray-600 mt-1">Images and videos attached to this product</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-xl bg-primary-100 text-primary-700 text-xs font-extrabold">
                            {{ $media->count() }} files
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden">
                        <div class="aspect-[4/3] bg-gray-100 relative">
                            @if ($cover)
                                <img id="mainMediaImage" src="{{ asset('storage/' . $cover->file_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @elseif ($videos->first())
                                <video controls class="w-full h-full object-cover bg-black">
                                    <source src="{{ asset('storage/' . $videos->first()->file_path) }}">
                                </video>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <div class="text-center">
                                        <div class="mx-auto w-12 h-12 rounded-2xl bg-white border border-gray-200 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7h2l2-2h10l2 2h2v12H3V7z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 11a3 3 0 100 6 3 3 0 000-6z" />
                                            </svg>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold">No media uploaded</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($images->count() > 1)
                        <div class="mt-4 grid grid-cols-5 gap-3">
                            @foreach ($images as $img)
                                <button type="button"
                                    class="thumbBtn rounded-xl overflow-hidden border border-gray-200 bg-white hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                                    data-src="{{ asset('storage/' . $img->file_path) }}">
                                    <img src="{{ asset('storage/' . $img->file_path) }}" alt="Thumbnail"
                                        class="w-full h-16 object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    @if ($videos->count() > 0)
                        <div class="mt-5">
                            <h3 class="text-sm font-extrabold text-gray-900 mb-3">Videos</h3>
                            <div class="space-y-3">
                                @foreach ($videos as $vid)
                                    <div class="rounded-2xl border border-gray-200 overflow-hidden bg-black">
                                        <video controls class="w-full">
                                            <source src="{{ asset('storage/' . $vid->file_path) }}">
                                        </video>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="xl:col-span-7 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="stat-card gradient-primary text-white rounded-2xl p-6 shadow-sm">
                    <p class="text-white/80 text-sm font-semibold">Category</p>
                    <p class="text-xl font-extrabold mt-2">{{ $categoryName }}</p>
                    <p class="text-white/80 text-sm font-semibold mt-3">SKU</p>
                    <p class="text-sm font-extrabold mt-1">{{ $sku }}</p>
                </div>

                <div class="stat-card gradient-success text-white rounded-2xl p-6 shadow-sm">
                    <p class="text-white/80 text-sm font-semibold">Stock</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $stock }}</p>
                    <div class="mt-3">
                        @if ($isInStock)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/15 text-white text-xs font-extrabold">
                                <span class="w-2 h-2 rounded-full bg-white"></span>
                                In Stock
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/15 text-white text-xs font-extrabold">
                                <span class="w-2 h-2 rounded-full bg-white"></span>
                                Out of Stock
                            </span>
                        @endif
                    </div>
                </div>

                <div class="stat-card gradient-warning text-white rounded-2xl p-6 shadow-sm">
                    <p class="text-white/80 text-sm font-semibold">Pricing</p>
                    <p class="text-xl font-extrabold mt-2">PKR {{ number_format($discountedPrice, 2) }}</p>
                    @if ($actualPrice > $discountedPrice)
                        <p class="text-white/80 text-sm font-semibold mt-3">Was</p>
                        <p class="text-sm font-extrabold mt-1 line-through">PKR {{ number_format($actualPrice, 2) }}</p>
                        <p class="text-white/90 text-xs font-extrabold mt-2">
                            {{ rtrim(rtrim(number_format((float) $product->discount_percentage, 2), '0'), '.') }}% OFF
                        </p>
                    @else
                        <p class="text-white/80 text-sm font-semibold mt-3">Actual</p>
                        <p class="text-sm font-extrabold mt-1">PKR {{ number_format($actualPrice, 2) }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Stock Breakdown</h2>
                        <p class="text-sm text-gray-600 mt-1">Size-wise inventory summary</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">Small S</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ (int) $product->small_stock }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">Medium M</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ (int) $product->medium_stock }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">Large L</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ (int) $product->large_stock }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">XL</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ (int) $product->xl_stock }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-extrabold text-gray-900">Product Details</h2>
                    <p class="text-sm text-gray-600 mt-1">All information from your product schema</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <p class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">Description</p>
                        <div class="text-sm font-medium text-gray-900 mt-2 whitespace-pre-line">
                            {{ $product->description ?: '—' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $fields = [
                                'Bottom Style' => $product->bottom_style,
                                'Color Type' => $product->color_type,
                                'Lining Attached' => $product->lining_attached,
                                'Number Of Pieces' => $product->number_of_pieces,
                                'Product Type' => $product->product_type,
                                'Season' => $product->season,
                                'Shirt Fabric' => $product->shirt_fabric,
                                'Top Style' => $product->top_style,
                                'Trouser Fabrics' => $product->trouser_fabrics,
                                'Created At' => optional($product->created_at)->format('M d, Y h:i A'),
                                'Updated At' => optional($product->updated_at)->format('M d, Y h:i A'),
                            ];
                        @endphp

                        @foreach ($fields as $label => $value)
                            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                <p class="text-xs font-extrabold tracking-wider text-gray-600 uppercase">{{ $label }}</p>
                                <p class="text-sm font-extrabold text-gray-900 mt-2">
                                    {{ $value ?: '—' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const main = document.getElementById('mainMediaImage');
                if (!main) return;

                document.querySelectorAll('.thumbBtn').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const src = btn.getAttribute('data-src');
                        if (src) main.src = src;
                    });
                });
            })();
        </script>
    @endpush
@endsection