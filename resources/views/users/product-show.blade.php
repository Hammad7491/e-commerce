@extends('users.layouts.app')

@section('title', $product->name)

@section('content')
    @php
        $wishlistIds = session()->get('wishlist', []);
        $inWishlist = in_array($product->id, $wishlistIds);

        $media = $product->media ?? collect();

        $allMedia = $media->values()->map(function ($item) {
            return [
                'src' => asset('storage/' . $item->file_path),
                'type' => $item->file_type,
            ];
        })->values();

        if ($allMedia->isEmpty() && $product->firstMedia) {
            $allMedia = collect([
                [
                    'src' => asset('storage/' . $product->firstMedia->file_path),
                    'type' => $product->firstMedia->file_type,
                ],
            ]);
        }

        $actualPrice = (float) $product->actual_price;
        $discountedPrice = (float) $product->discounted_price;
        $discountPercentage = (float) $product->discount_percentage;
        $hasDiscount = $actualPrice > $discountedPrice;

        $productDetails = [
            'Bottom Style' => $product->bottom_style,
            'Color Type' => $product->color_type,
            'Product ID' => $product->product_code,
            'Lining Attached' => $product->lining_attached,
            'Number Of Pieces' => $product->number_of_pieces,
            'Product Type' => $product->product_type,
            'Season' => $product->season,
            'Shirt Fabric' => $product->shirt_fabric,
            'Top Style' => $product->top_style,
            'Trouser Fabrics' => $product->trouser_fabrics,
        ];

        $sizes = [
            'S' => (int) $product->small_stock,
            'M' => (int) $product->medium_stock,
            'L' => (int) $product->large_stock,
            'XL' => (int) $product->xl_stock,
        ];
    @endphp

    <style>
        .product-show-page {
            padding: 22px 14px 46px;
        }

        @media (min-width: 768px) {
            .product-show-page {
                padding: 28px 20px 56px;
            }
        }

        @media (min-width: 1280px) {
            .product-show-page {
                padding: 30px 24px 60px;
            }
        }

        .product-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 28px;
        }

        @media (min-width: 1024px) {
            .product-layout {
                grid-template-columns: minmax(0, 1.1fr) minmax(360px, 430px);
                align-items: start;
            }
        }

        .gallery-block {
            display: grid;
            grid-template-columns: 56px minmax(0, 1fr);
            gap: 14px;
        }

        @media (min-width: 768px) {
            .gallery-block {
                grid-template-columns: 70px minmax(0, 1fr);
                gap: 16px;
            }
        }

        .thumbs-column {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 760px;
            overflow-y: auto;
            padding-right: 2px;
        }

        .thumb-btn {
            width: 100%;
            aspect-ratio: 3 / 4;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            background: #f8f8f8;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .thumb-btn:hover,
        .thumb-btn.active {
            border-color: #111827;
            box-shadow: 0 0 0 1px #111827 inset;
        }

        .thumb-btn img,
        .thumb-btn video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .main-media-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            background: #f7f5f2;
            border: 1px solid #ececec;
        }

        .main-media-frame {
            width: 100%;
            aspect-ratio: 4 / 5;
            position: relative;
            overflow: hidden;
            background: #f7f5f2;
        }

        .main-media-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .main-media-slide img,
        .main-media-slide video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .main-media-empty {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 500;
        }

        .media-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border-radius: 9999px;
            border: 1px solid rgba(17, 24, 39, 0.08);
            background: rgba(255, 255, 255, 0.92);
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            z-index: 5;
        }

        .media-nav-btn:hover {
            background: #fff;
        }

        .media-nav-prev {
            left: 14px;
        }

        .media-nav-next {
            right: 14px;
        }

        .product-side {
            position: relative;
        }

        .product-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .product-name {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.12;
            color: #111827;
            letter-spacing: -0.025em;
            margin: 0;
        }

        .product-subtitle {
            margin-top: 4px;
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
        }

        .top-action-icons {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .wishlist-form {
            margin: 0;
        }

        .circle-icon-btn {
            width: 48px;
            height: 48px;
            border-radius: 9999px;
            border: 1px solid #d7dbe0;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #111827;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .circle-icon-btn:hover {
            background: #f9fafb;
            transform: translateY(-1px);
        }

        .price-block {
            margin-top: 18px;
        }

        .current-price {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            color: #111827;
            letter-spacing: -0.02em;
        }

        .price-row {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .old-price {
            font-size: 17px;
            color: #6b7280;
            text-decoration: line-through;
            font-weight: 500;
        }

        .off-text {
            font-size: 17px;
            font-weight: 700;
            color: #ef4444;
        }

        .stock-note {
            margin-top: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .stock-note.low {
            color: #dc2626;
        }

        .stock-note.good {
            color: #166534;
        }

        .section-title-row {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
        }

        .size-grid {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .size-btn {
            min-width: 56px;
            height: 44px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #fff;
            color: #374151;
            font-size: 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            position: relative;
        }

        .size-btn.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
        }

        .size-btn.disabled {
            opacity: 0.45;
            cursor: not-allowed;
            background: #f3f4f6;
        }

        .qty-wrap {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 42px;
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #f9fafb;
            color: #374151;
            font-size: 24px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .qty-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .qty-value {
            min-width: 24px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .primary-btn {
            margin-top: 18px;
            width: 100%;
            height: 56px;
            border-radius: 12px;
            background: #111111;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            border: 0;
            transition: 0.2s ease;
        }

        .primary-btn:hover {
            background: #222;
        }

        .primary-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
            background: #111111;
        }

        .secondary-btn {
            margin-top: 12px;
            width: 100%;
            height: 54px;
            border-radius: 12px;
            background: #fff;
            color: #374151;
            font-size: 17px;
            font-weight: 600;
            border: 1px solid #d1d5db;
            transition: 0.2s ease;
        }

        .secondary-btn:hover {
            background: #f9fafb;
        }

        .details-card {
            margin-top: 28px;
        }

        .details-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
            border: 1px solid #dcdcdc;
            background: #fff;
        }

        .details-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            font-size: 14px;
            line-height: 1.45;
            vertical-align: top;
        }

        .details-table tr:last-child td {
            border-bottom: 0;
        }

        .details-table td:last-child {
            border-right: 0;
        }

        .details-table td:first-child {
            width: 42%;
            font-weight: 600;
            color: #374151;
            background: #fafafa;
        }

        .details-table td:last-child {
            color: #4b5563;
            font-weight: 500;
        }

        .info-sections {
            margin-top: 28px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .info-card {
            position: relative;
            border-radius: 22px;
            padding: 24px 26px;
            background:
                linear-gradient(135deg, rgba(255,255,255,0.18), rgba(255,255,255,0.04)),
                linear-gradient(180deg, rgba(17,24,39,0.02), rgba(17,24,39,0.00));
            border: 1px solid rgba(17,24,39,0.08);
            box-shadow: 0 10px 30px rgba(17,24,39,0.04);
            overflow: hidden;
        }

        .info-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(17,24,39,0.05), transparent 40%);
            pointer-events: none;
        }

        .info-title {
            position: relative;
            font-size: 19px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .info-text {
            position: relative;
            font-size: 15px;
            line-height: 1.9;
            color: #4b5563;
            white-space: pre-line;
        }

        .slide-enter-right {
            animation: slideInRight .35s ease;
        }

        .slide-enter-left {
            animation: slideInLeft .35s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: .65;
                transform: translateX(70px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: .65;
                transform: translateX(-70px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 767px) {
            .gallery-block {
                grid-template-columns: 1fr;
            }

            .thumbs-column {
                order: 2;
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                padding-bottom: 2px;
            }

            .thumb-btn {
                width: 62px;
                min-width: 62px;
                aspect-ratio: 3 / 4;
            }

            .main-media-card {
                order: 1;
            }

            .product-name {
                font-size: 22px;
            }

            .current-price {
                font-size: 24px;
            }

            .details-table td {
                font-size: 13px;
                padding: 10px 12px;
            }

            .circle-icon-btn {
                width: 44px;
                height: 44px;
            }

            .media-nav-btn {
                width: 38px;
                height: 38px;
            }

            .info-card {
                padding: 20px 18px;
                border-radius: 18px;
            }
        }
    </style>

    <div
        class="product-show-page"
        x-data='{
            mediaItems: @json($allMedia->values()),
            sizeStockMap: @json($sizes),
            activeIndex: 0,
            qty: 1,
            selectedSize: "",
            direction: "right",
            animationClass: "slide-enter-right",
            unitPrice: {{ $discountedPrice }},

            init() {
                this.selectedSize = "";
            },

            get activeItem() {
                return this.mediaItems[this.activeIndex] ?? null;
            },

            setMedia(index) {
                this.direction = index > this.activeIndex ? "right" : "left";
                this.animationClass = this.direction === "right" ? "slide-enter-right" : "slide-enter-left";
                this.activeIndex = index;
            },

            nextMedia() {
                if (!this.mediaItems.length) return;
                this.direction = "right";
                this.animationClass = "slide-enter-right";
                this.activeIndex = (this.activeIndex + 1) % this.mediaItems.length;
            },

            prevMedia() {
                if (!this.mediaItems.length) return;
                this.direction = "left";
                this.animationClass = "slide-enter-left";
                this.activeIndex = (this.activeIndex - 1 + this.mediaItems.length) % this.mediaItems.length;
            },

            get currentStock() {
                if (!this.selectedSize) return 0;
                return parseInt(this.sizeStockMap[this.selectedSize] || 0);
            },

            selectSize(size) {
                const stock = parseInt(this.sizeStockMap[size] || 0);
                if (stock <= 0) return;
                this.selectedSize = size;
                if (this.qty > stock) this.qty = stock;
                if (this.qty < 1) this.qty = 1;
            },

            increaseQty() {
                if (!this.selectedSize) return;
                if (this.qty < this.currentStock) this.qty++;
            },

            decreaseQty() {
                if (this.qty > 1) this.qty--;
            },

            get totalPrice() {
                return this.unitPrice * this.qty;
            },

            formatPrice(value) {
                return new Intl.NumberFormat("en-PK", {
                    maximumFractionDigits: 0
                }).format(value);
            },

            get canAddToBag() {
                return this.selectedSize !== "" && this.currentStock > 0 && this.qty >= 1 && this.qty <= this.currentStock;
            }
        }'
    >
        <div class="product-layout">
            <div>
                <div class="gallery-block">
                    <div class="thumbs-column">
                        @foreach ($allMedia as $index => $item)
                            <button
                                type="button"
                                class="thumb-btn"
                                :class="{ 'active': activeIndex === {{ $index }} }"
                                @click="setMedia({{ $index }})"
                            >
                                @if ($item['type'] === 'image')
                                    <img src="{{ $item['src'] }}" alt="{{ $product->name }}">
                                @else
                                    <video muted playsinline>
                                        <source src="{{ $item['src'] }}">
                                    </video>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    <div class="main-media-card">
                        <button
                            type="button"
                            class="media-nav-btn media-nav-prev"
                            @click="prevMedia()"
                            x-show="mediaItems.length > 1"
                        >
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 5-7 7 7 7"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="media-nav-btn media-nav-next"
                            @click="nextMedia()"
                            x-show="mediaItems.length > 1"
                        >
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 5 7 7-7 7"/>
                            </svg>
                        </button>

                        <div class="main-media-frame">
                            <template x-if="activeItem && activeItem.type === 'image'">
                                <div class="main-media-slide" :class="animationClass" :key="activeItem.src + '-' + activeIndex">
                                    <img :src="activeItem.src" alt="{{ $product->name }}">
                                </div>
                            </template>

                            <template x-if="activeItem && activeItem.type === 'video'">
                                <div class="main-media-slide" :class="animationClass" :key="activeItem.src + '-' + activeIndex">
                                    <video controls :src="activeItem.src"></video>
                                </div>
                            </template>

                            <template x-if="!activeItem">
                                <div class="main-media-empty">No media available</div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="info-sections">
                    @if (!empty($product->description))
                        <div class="info-card">
                            <div class="info-title">Additional Description</div>
                            <div class="info-text">{{ $product->description }}</div>
                        </div>
                    @endif

                    <div class="info-card">
                        <div class="info-title">Disclaimer</div>
                        <div class="info-text">Actual product color may vary slightly from the image.</div>
                    </div>
                </div>
            </div>

            <div class="product-side">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="product-header">
                    <div>
                        <h1 class="product-name">{{ $product->name }}</h1>
                        <div class="product-subtitle">{{ $product->category?->name ?? 'Women Collection' }}</div>
                    </div>

                    <div class="top-action-icons">
                        <form action="{{ route('users.wishlist.store', $product) }}" method="POST" class="wishlist-form">
                            @csrf
                            <button type="submit" class="circle-icon-btn" title="Wishlist">
                                <svg width="22" height="22" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="price-block">
                    <div class="current-price">PKR {{ number_format($discountedPrice, 0) }}</div>

                    @if ($hasDiscount)
                        <div class="price-row">
                            <div class="old-price">PKR {{ number_format($actualPrice, 0) }}</div>
                            <div class="off-text">-{{ rtrim(rtrim(number_format($discountPercentage, 2), '0'), '.') }}%</div>
                        </div>
                    @endif

                    <div
                        class="stock-note"
                        :class="currentStock > 0 && currentStock <= 3 ? 'low' : 'good'"
                        x-show="selectedSize"
                    >
                        <span x-show="currentStock > 0">
                            <span x-text="currentStock"></span> left in stock for size <span x-text="selectedSize"></span>
                        </span>
                        <span x-show="currentStock <= 0">Out of stock</span>
                    </div>
                </div>

                <div class="section-title-row">
                    <div class="section-title">Size</div>
                </div>

                <div class="size-grid">
                    @foreach ($sizes as $sizeName => $stock)
                        <button
                            type="button"
                            class="size-btn {{ $stock <= 0 ? 'disabled' : '' }}"
                            :class="{ 'active': selectedSize === '{{ $sizeName }}' }"
                            @click="selectSize('{{ $sizeName }}')"
                            {{ $stock <= 0 ? 'disabled' : '' }}
                        >
                            {{ $sizeName }}
                        </button>
                    @endforeach
                </div>

                <div class="section-title-row">
                    <div class="section-title">Quantity</div>
                </div>

                <div class="qty-wrap">
                    <button type="button" class="qty-btn" @click="decreaseQty()" :disabled="qty <= 1">−</button>
                    <div class="qty-value" x-text="qty"></div>
                    <button type="button" class="qty-btn" @click="increaseQty()" :disabled="!selectedSize || qty >= currentStock">+</button>
                </div>

                <button type="button" class="primary-btn" :disabled="!canAddToBag">
                    <span x-show="!selectedSize">Select Size To Add</span>
                    <span x-show="selectedSize && canAddToBag">
                        Add To Bag (PKR <span x-text="formatPrice(totalPrice)"></span>)
                    </span>
                    <span x-show="selectedSize && !canAddToBag && currentStock <= 0">Out Of Stock</span>
                </button>

                <button type="button" class="secondary-btn">
                    Buy Now
                </button>

                <div class="details-card">
                    <div class="details-title">Product Details</div>

                    <table class="details-table">
                        <tbody>
                            @foreach ($productDetails as $label => $value)
                                @if(!empty($value))
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection