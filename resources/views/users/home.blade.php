@extends('users.layouts.app')

@section('title', 'Home')

@section('content')
    @php
        $wishlistIds = session()->get('wishlist', []);
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }

        .products-page {
            padding: 1rem;
        }

        @media (min-width: 768px) {
            .products-page {
                padding: 1.25rem;
            }
        }

        @media (min-width: 1024px) {
            .products-page {
                padding: 1.5rem;
            }
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 2rem 1rem;
        }

        @media (min-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .product-card-wrap {
            width: 100%;
        }

        .product-card {
            width: 100%;
            max-width: 170px;
            margin: 0 auto;
        }

        @media (min-width: 640px) {
            .product-card {
                max-width: 220px;
            }
        }

        @media (min-width: 1024px) {
            .product-card {
                max-width: 100%;
            }
        }

        .product-image-link {
            display: block;
            position: relative;
            overflow: hidden;
            border-radius: 4px;
            background: #f3f3f3;
        }

        .product-image-frame {
            width: 100%;
            aspect-ratio: 3 / 4;
            background: #f3f3f3;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center top;
            display: block;
            transition: transform 0.45s ease;
        }

        .group:hover .product-image {
            transform: scale(1.02);
        }

        .product-video,
        .product-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .product-video {
            background: #000;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .product-empty {
            background: #f3f3f3;
            color: #9ca3af;
            font-size: 0.875rem;
            font-weight: 400;
        }

        .discount-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            display: inline-flex;
            align-items: center;
            padding: 0.32rem 0.5rem;
            border-radius: 4px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
            letter-spacing: -0.01em;
            z-index: 10;
        }

        @media (min-width: 640px) {
            .discount-badge {
                top: 0.75rem;
                left: 0.75rem;
                padding: 0.4rem 0.65rem;
                font-size: 12px;
            }
        }

        .wishlist-btn-form {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            z-index: 10;
            margin: 0;
        }

        .wishlist-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: transform 0.2s ease, color 0.2s ease;
            background: transparent;
            border: 0;
            padding: 0;
            cursor: pointer;
        }

        .wishlist-btn.active {
            color: #ef4444;
        }

        .wishlist-btn:hover {
            transform: scale(1.05);
        }

        .wishlist-icon {
            width: 1.2rem;
            height: 1.2rem;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.35));
        }

        @media (min-width: 640px) {
            .wishlist-icon {
                width: 1.6rem;
                height: 1.6rem;
            }
        }

        @media (min-width: 768px) {
            .wishlist-icon {
                width: 1.8rem;
                height: 1.8rem;
            }
        }

        .product-meta {
            padding-top: 0.65rem;
        }

        .product-meta-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.35rem;
        }

        .product-meta-main {
            min-width: 0;
            flex: 1 1 auto;
        }

        .product-prices {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.15rem;
        }

        .sale-price {
            font-size: 14px;
            font-weight: 600;
            line-height: 1;
            letter-spacing: -0.02em;
            color: #ef4444;
        }

        .old-price {
            font-size: 11px;
            font-weight: 400;
            line-height: 1;
            letter-spacing: -0.01em;
            color: #6b7280;
            text-decoration: line-through;
        }

        .product-title {
            margin-top: 0.55rem;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.3;
            letter-spacing: -0.03em;
            color: #1f2937;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 36px;
        }

        .product-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .product-title a:hover {
            color: #000;
        }

        .cart-btn {
            flex-shrink: 0;
            margin-top: 0.125rem;
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: background-color 0.2s ease;
        }

        .cart-btn:hover {
            background: #f9fafb;
        }

        .cart-icon {
            width: 0.9rem;
            height: 0.9rem;
            color: #111827;
        }

        @media (min-width: 640px) {
            .sale-price {
                font-size: 16px;
            }

            .old-price {
                font-size: 13px;
            }

            .product-title {
                font-size: 16px;
                min-height: 40px;
            }

            .cart-btn {
                width: 2.5rem;
                height: 2.5rem;
            }

            .cart-icon {
                width: 1rem;
                height: 1rem;
            }
        }

        @media (min-width: 1024px) {
            .sale-price {
                font-size: 17px;
            }

            .old-price {
                font-size: 14px;
            }

            .product-title {
                font-size: 17px;
            }
        }

        .wishlist-toast {
            position: fixed;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%);
            background: rgba(17, 24, 39, 0.96);
            color: #fff;
            min-width: 280px;
            max-width: calc(100vw - 32px);
            padding: 14px 18px;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.24);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wishlist-toast-thumb {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            background: #fff;
        }

        .wishlist-toast-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .wishlist-toast-text {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
        }
    </style>

    <div
        class="products-page"
        x-data="{
            totalProducts: {{ $products->count() }},
            visibleCount: 0,
            columns: 2,
            wishlist: @js(array_map('intval', $wishlistIds)),
            toastOpen: false,
            toastMessage: '',
            toastImage: '',

            detectColumns() {
                this.columns = window.innerWidth >= 1024 ? 4 : 2;
            },

            get initialRows() {
                return 2;
            },

            get rowsPerLoad() {
                return 2;
            },

            get initialStep() {
                return this.columns * this.initialRows;
            },

            get loadStep() {
                return this.columns * this.rowsPerLoad;
            },

            initGrid() {
                this.detectColumns();
                this.visibleCount = Math.min(this.initialStep, this.totalProducts);

                window.addEventListener('resize', () => {
                    const oldColumns = this.columns;
                    this.detectColumns();

                    if (oldColumns !== this.columns) {
                        const shownRows = Math.max(1, Math.ceil(this.visibleCount / oldColumns));
                        this.visibleCount = Math.min(shownRows * this.columns, this.totalProducts);
                    }
                });
            },

            showMore() {
                this.visibleCount = Math.min(this.visibleCount + this.loadStep, this.totalProducts);
            },

            isInWishlist(id) {
                return this.wishlist.includes(parseInt(id));
            },

            showToast(message, image = '') {
                this.toastMessage = message;
                this.toastImage = image;
                this.toastOpen = true;

                clearTimeout(this.toastTimer);
                this.toastTimer = setTimeout(() => {
                    this.toastOpen = false;
                }, 2200);
            },

            async toggleWishlist(productId, storeUrl, destroyUrl, image = '') {
                productId = parseInt(productId);
                const alreadyInWishlist = this.isInWishlist(productId);
                const url = alreadyInWishlist ? destroyUrl : storeUrl;
                const method = alreadyInWishlist ? 'DELETE' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const text = await response.text();
                    let data = {};

                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response: ' + text);
                    }

                    if (!response.ok) {
                        throw new Error(data.message || 'Request failed');
                    }

                    if (data.action === 'added') {
                        if (!this.isInWishlist(productId)) {
                            this.wishlist.push(productId);
                        }
                        this.showToast(data.message || 'Item added to favourites', image);
                    } else if (data.action === 'removed') {
                        this.wishlist = this.wishlist.filter(id => id !== productId);
                        this.showToast(data.message || 'Item removed from favourites', image);
                    }
                } catch (error) {
                    console.error('Wishlist AJAX error:', error);
                    this.showToast('Something went wrong');
                }
            }
        }"
        x-init="initGrid()"
    >
        <div class="mb-8 md:mb-10">
            <h1 class="text-3xl lg:text-4xl font-semibold tracking-[-0.03em] text-gray-900 leading-tight">
                Women Ready To Wear
            </h1>
            <p class="mt-3 text-[15px] lg:text-[17px] font-normal text-gray-500 leading-relaxed max-w-2xl">
                Explore elegant pieces from our premium women collection.
            </p>
        </div>

        @if ($products->count())
            <div class="products-grid">
                @foreach ($products as $index => $product)
                    @php
                        $firstMedia = $product->firstMedia;
                        $hasDiscount = (float) $product->actual_price > (float) $product->discounted_price;
                        $discount = rtrim(rtrim(number_format((float) $product->discount_percentage, 2), '0'), '.');
                        $thumbImage = $firstMedia && $firstMedia->file_type === 'image'
                            ? asset('storage/' . $firstMedia->file_path)
                            : '';
                    @endphp

                    <div x-show="{{ $index }} < visibleCount" x-transition x-cloak class="group product-card-wrap">
                        <div class="product-card">
                            <div class="relative">
                                <a href="{{ route('users.product', $product) }}" class="product-image-link">
                                    <div class="product-image-frame">
                                        @if ($firstMedia && $firstMedia->file_type === 'image')
                                            <img
                                                src="{{ asset('storage/' . $firstMedia->file_path) }}"
                                                alt="{{ $product->name }}"
                                                class="product-image"
                                            >
                                        @elseif ($firstMedia && $firstMedia->file_type === 'video')
                                            <div class="product-video">Video Preview</div>
                                        @else
                                            <div class="product-empty">No Image</div>
                                        @endif
                                    </div>
                                </a>

                                @if ($hasDiscount)
                                    <div class="discount-badge">
                                        -{{ $discount }}%
                                    </div>
                                @endif

                                <div class="wishlist-btn-form">
                                    <button
                                        type="button"
                                        class="wishlist-btn"
                                        :class="{ 'active': isInWishlist({{ $product->id }}) }"
                                        @click.prevent="toggleWishlist(
                                            {{ $product->id }},
                                            '{{ route('users.wishlist.store', $product) }}',
                                            '{{ route('users.wishlist.destroy', $product) }}',
                                            '{{ $thumbImage }}'
                                        )"
                                        title="Wishlist"
                                    >
                                        <svg
                                            class="wishlist-icon"
                                            :fill="isInWishlist({{ $product->id }}) ? 'currentColor' : 'none'"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="product-meta">
                                <div class="product-meta-row">
                                    <div class="product-meta-main">
                                        <div class="product-prices">
                                            <span class="sale-price">
                                                PKR {{ number_format((float) $product->discounted_price, 0) }}
                                            </span>

                                            @if ($hasDiscount)
                                                <span class="old-price">
                                                    PKR {{ number_format((float) $product->actual_price, 0) }}
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="product-title">
                                            <a href="{{ route('users.product', $product) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                    </div>

                                    <button type="button" class="cart-btn">
                                        <svg class="cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.2 1.6a1 1 0 0 0 .8 1.4H19m-9 4a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center" x-show="visibleCount < totalProducts" x-cloak>
                <button
                    @click="showMore()"
                    type="button"
                    class="inline-flex items-center px-8 py-3 rounded-[4px] bg-black text-white text-[15px] font-medium hover:bg-gray-800 transition shadow-sm"
                >
                    Show More
                </button>
            </div>
        @else
            <div class="bg-white rounded-[4px] p-14 text-center text-gray-500 text-lg">
                No products found.
            </div>
        @endif

        <div class="wishlist-toast" x-show="toastOpen" x-transition.opacity.duration.250ms x-cloak>
            <template x-if="toastImage">
                <div class="wishlist-toast-thumb">
                    <img :src="toastImage" alt="Wishlist item">
                </div>
            </template>
            <div class="wishlist-toast-text" x-text="toastMessage"></div>
        </div>
    </div>
@endsection