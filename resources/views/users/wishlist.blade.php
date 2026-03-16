@extends('users.layouts.app')

@section('title', 'Wishlist')

@section('content')
    @php
        $wishlistIds = session()->get('wishlist', []);
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }

        .wishlist-page {
            padding: 24px 16px 52px;
        }

        @media (min-width: 768px) {
            .wishlist-page {
                padding: 28px 20px 60px;
            }
        }

        @media (min-width: 1280px) {
            .wishlist-page {
                padding: 32px 24px 68px;
            }
        }

        .wishlist-header {
            margin-bottom: 30px;
        }

        .wishlist-title {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.05;
            color: #111827;
            letter-spacing: -0.04em;
            margin: 0;
        }

        .wishlist-subtitle {
            margin-top: 10px;
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 22px;
        }

        @media (min-width: 640px) {
            .wishlist-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .wishlist-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .wishlist-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .wishlist-card {
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #fcfcfd 100%);
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            box-shadow: 0 8px 26px rgba(17, 24, 39, 0.05);
        }

        .wishlist-card:hover {
            transform: translateY(-4px);
            border-color: #dbe2ea;
            box-shadow: 0 18px 38px rgba(17, 24, 39, 0.10);
        }

        .wishlist-image-wrap {
            position: relative;
            background: linear-gradient(180deg, #f8f8f8 0%, #f2f4f7 100%);
            overflow: hidden;
        }

        .wishlist-image-link {
            display: block;
            text-decoration: none;
        }

        .wishlist-image {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            display: block;
            transition: transform 0.45s ease;
        }

        .wishlist-card:hover .wishlist-image {
            transform: scale(1.03);
        }

        .wishlist-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 11px;
            border-radius: 9999px;
            background: rgba(17, 24, 39, 0.82);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.01em;
            backdrop-filter: blur(8px);
        }

        .wishlist-body {
            padding: 18px 18px 20px;
        }

        .wishlist-category {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .wishlist-name {
            margin: 8px 0 0;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            line-height: 1.25;
            letter-spacing: -0.02em;
            min-height: 50px;
        }

        .wishlist-name a {
            color: inherit;
            text-decoration: none;
        }

        .wishlist-name a:hover {
            color: #000;
        }

        .wishlist-price-row {
            margin-top: 14px;
            display: flex;
            align-items: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .wishlist-price {
            font-size: 24px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .wishlist-old-price {
            font-size: 15px;
            color: #6b7280;
            text-decoration: line-through;
            font-weight: 500;
            line-height: 1;
        }

        .wishlist-divider {
            margin-top: 16px;
            height: 1px;
            background: linear-gradient(90deg, rgba(17,24,39,0.10), rgba(17,24,39,0.03));
        }

        .wishlist-actions {
            margin-top: 16px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .wishlist-view-btn,
        .wishlist-remove-text-btn {
            height: 46px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .wishlist-view-btn {
            background: #111827;
            color: #fff;
            border: 1px solid #111827;
            box-shadow: 0 8px 20px rgba(17, 24, 39, 0.10);
        }

        .wishlist-view-btn:hover {
            background: #000;
            border-color: #000;
        }

        .wishlist-remove-text-btn {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .wishlist-remove-text-btn:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .wishlist-empty {
            padding: 70px 24px;
            text-align: center;
            border: 1px dashed #d1d5db;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255,255,255,0.92), rgba(248,250,252,0.92));
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
        }

        .wishlist-empty-title {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin: 0;
            letter-spacing: -0.03em;
        }

        .wishlist-empty-text {
            margin-top: 12px;
            color: #6b7280;
            font-size: 16px;
            font-weight: 500;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        .wishlist-empty-btn {
            margin-top: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 26px;
            border-radius: 14px;
            background: #111827;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.12);
            transition: 0.2s ease;
        }

        .wishlist-empty-btn:hover {
            background: #000;
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
        class="wishlist-page"
        x-data="wishlistPageData()"
        x-init="init()"
    >
        <div class="wishlist-header">
            <h1 class="wishlist-title">My Wishlist</h1>
            <div class="wishlist-subtitle">
                Save your favorite items and view them anytime.
            </div>
        </div>

        @if ($products->count())
            <template x-if="wishlist.length > 0">
                <div class="wishlist-grid">
                    @foreach ($products as $product)
                        @php
                            $firstMedia = $product->firstMedia;
                            $hasDiscount = (float) $product->actual_price > (float) $product->discounted_price;
                            $thumbImage = $firstMedia && $firstMedia->file_type === 'image'
                                ? asset('storage/' . $firstMedia->file_path)
                                : 'https://placehold.co/600x750?text=No+Image';
                        @endphp

                        <div class="wishlist-card" x-show="isInWishlist({{ $product->id }})" x-transition>
                            <div class="wishlist-image-wrap">
                                <a href="{{ route('users.product', $product) }}" class="wishlist-image-link">
                                    @if ($firstMedia && $firstMedia->file_type === 'image')
                                        <img
                                            src="{{ asset('storage/' . $firstMedia->file_path) }}"
                                            alt="{{ $product->name }}"
                                            class="wishlist-image"
                                        >
                                    @else
                                        <img
                                            src="https://placehold.co/600x750?text=No+Image"
                                            alt="{{ $product->name }}"
                                            class="wishlist-image"
                                        >
                                    @endif
                                </a>

                                <div class="wishlist-badge">
                                    Saved Item
                                </div>
                            </div>

                            <div class="wishlist-body">
                                <div class="wishlist-category">
                                    {{ $product->category?->name ?? 'Women Collection' }}
                                </div>

                                <h3 class="wishlist-name">
                                    <a href="{{ route('users.product', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <div class="wishlist-price-row">
                                    <div class="wishlist-price">
                                        PKR {{ number_format((float) $product->discounted_price, 0) }}
                                    </div>

                                    @if ($hasDiscount)
                                        <div class="wishlist-old-price">
                                            PKR {{ number_format((float) $product->actual_price, 0) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="wishlist-divider"></div>

                                <div class="wishlist-actions">
                                    <a href="{{ route('users.product', $product) }}" class="wishlist-view-btn">
                                        View Product
                                    </a>

                                    <button
                                        type="button"
                                        class="wishlist-remove-text-btn"
                                        @click.prevent="removeWishlist(
                                            {{ $product->id }},
                                            '{{ route('users.wishlist.destroy', $product) }}',
                                            '{{ $thumbImage }}'
                                        )"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </template>

            <template x-if="wishlist.length === 0">
                <div class="wishlist-empty">
                    <h2 class="wishlist-empty-title">Your wishlist is empty</h2>
                    <div class="wishlist-empty-text">
                        Start adding the products you love and they will appear here in your personal wishlist.
                    </div>

                    <a href="{{ route('home') }}" class="wishlist-empty-btn">
                        Continue Shopping
                    </a>
                </div>
            </template>
        @else
            <div class="wishlist-empty">
                <h2 class="wishlist-empty-title">Your wishlist is empty</h2>
                <div class="wishlist-empty-text">
                    Start adding the products you love and they will appear here in your personal wishlist.
                </div>

                <a href="{{ route('home') }}" class="wishlist-empty-btn">
                    Continue Shopping
                </a>
            </div>
        @endif

        <div
            class="wishlist-toast"
            x-show="toastOpen"
            x-transition.opacity.duration.250ms
            x-cloak
        >
            <template x-if="toastImage">
                <div class="wishlist-toast-thumb">
                    <img :src="toastImage" alt="Wishlist item">
                </div>
            </template>

            <div class="wishlist-toast-text" x-text="toastMessage"></div>
        </div>
    </div>

    <script>
        function wishlistPageData() {
            return {
                wishlist: @json(array_map('intval', $wishlistIds)),
                toastOpen: false,
                toastMessage: '',
                toastImage: '',

                init() {},

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

                async removeWishlist(productId, destroyUrl, image = '') {
                    productId = parseInt(productId);

                    try {
                        const response = await fetch(destroyUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const contentType = response.headers.get('content-type') || '';

                        if (!contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non JSON response:', text);
                            throw new Error('Server did not return JSON');
                        }

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Request failed');
                        }

                        this.wishlist = this.wishlist.filter(id => id !== productId);
                        this.showToast(data.message || 'Item removed from favourites', image);
                    } catch (error) {
                        console.error('Wishlist AJAX error:', error);
                        this.showToast('Something went wrong', image);
                    }
                }
            };
        }
    </script>
@endsection