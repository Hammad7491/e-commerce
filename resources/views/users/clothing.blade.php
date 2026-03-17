@extends('users.layouts.app')

@section('title', 'Women Ready To Wear')

@section('content')
    @php
        $wishlistIds = session()->get('wishlist', []);

        $allProducts = $products->map(function ($product) use ($wishlistIds) {
            $mediaItems = ($product->media ?? collect())
                ->filter(fn ($item) => $item->file_type === 'image')
                ->map(fn ($item) => asset('storage/' . $item->file_path))
                ->values()
                ->all();

            $firstMedia = $product->firstMedia;
            $mainImage = $firstMedia && $firstMedia->file_type === 'image'
                ? asset('storage/' . $firstMedia->file_path)
                : 'https://placehold.co/600x750?text=No+Image';

            if (empty($mediaItems)) {
                $mediaItems = [$mainImage];
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category_id' => $product->category_id,
                'category_name' => optional($product->category)->name ?? 'Uncategorized',
                'actual_price' => (float) $product->actual_price,
                'discounted_price' => (float) $product->discounted_price,
                'discount_percentage' => (float) $product->discount_percentage,
                'description' => $product->description,
                'image' => $mainImage,
                'images' => $mediaItems,
                'product_url' => route('users.product', $product),
                'wishlist_store_url' => route('users.wishlist.store', $product),
                'wishlist_destroy_url' => route('users.wishlist.destroy', $product),
                'in_wishlist' => in_array($product->id, $wishlistIds),
                'sizes' => [
                    'S' => (int) $product->small_stock,
                    'M' => (int) $product->medium_stock,
                    'L' => (int) $product->large_stock,
                    'XL' => (int) $product->xl_stock,
                ],
                'details' => array_filter([
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
                ], fn ($value) => !empty($value)),
            ];
        })->values();

        $firstCategory = $categories->first();
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }

        .clothing-page {
            padding: 22px 18px 56px;
        }

        @media (min-width: 1024px) {
            .clothing-page {
                padding: 26px 22px 64px;
            }
        }

        .collection-shell {
            max-width: 1080px;
            margin: 0 auto;
            width: 100%;
        }

        .collection-header {
            margin-bottom: 14px;
        }

        .collection-title {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.04;
            color: #0f172a;
            letter-spacing: -0.045em;
            margin: 0;
            text-transform: capitalize;
        }

        @media (min-width: 1024px) {
            .collection-title {
                font-size: 38px;
            }
        }

        .collection-subtitle {
            margin-top: 10px;
            font-size: 15px;
            font-weight: 500;
            color: #6b7280;
            line-height: 1.55;
        }

        .collection-count {
            margin-top: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
        }

        .category-strip-wrap {
            margin-top: 16px;
            margin-bottom: 20px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .category-strip-row {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .category-scroll-btn {
            width: 42px;
            height: 42px;
            border-radius: 9999px;
            border: 1px solid #d9dde3;
            background: #fff;
            color: #4b5563;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .category-scroll-btn:hover {
            background: #f9fafb;
            color: #111827;
        }

        .category-scroll-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .category-strip {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: none;
            scroll-behavior: smooth;
            flex: 1 1 auto;
            min-width: 0;
        }

        .category-strip::-webkit-scrollbar {
            display: none;
        }

        .category-chip {
            flex-shrink: 0;
            height: 38px;
            padding: 0 16px;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            line-height: 1;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .category-chip:hover {
            background: #eceff3;
            color: #111827;
            border-color: #d9dfe7;
        }

        .category-chip.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
            box-shadow: 0 6px 18px rgba(17, 24, 39, 0.10);
        }

        .products-fade {
            transition: opacity 0.18s ease, transform 0.18s ease;
        }

        .products-fade.loading {
            opacity: 0.35;
            transform: translateY(4px);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px 18px;
        }

        @media (min-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 32px 20px;
            }
        }

        .product-card {
            width: 100%;
        }

        .product-image-link {
            display: block;
            position: relative;
            overflow: hidden;
            border-radius: 4px;
            background: #f3f4f6;
            text-decoration: none;
        }

        .product-image-frame {
            width: 100%;
            aspect-ratio: 3 / 4;
            overflow: hidden;
            background: #f3f4f6;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.45s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.03);
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 4px;
            background: #ef4444;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.01em;
        }

        .wishlist-float-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            background: transparent;
            border: 0;
            padding: 0;
            cursor: pointer;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .wishlist-float-btn.active {
            color: #ef4444;
        }

        .wishlist-float-btn:hover {
            transform: scale(1.05);
        }

        .wishlist-float-icon {
            width: 1.5rem;
            height: 1.5rem;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.35));
        }

        .product-meta {
            padding-top: 10px;
        }

        .product-meta-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .product-meta-main {
            min-width: 0;
            flex: 1 1 auto;
        }

        .sale-price {
            font-size: 17px;
            font-weight: 700;
            color: #ef4444;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .old-price {
            margin-top: 5px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: line-through;
            line-height: 1.1;
        }

        .product-title {
            margin-top: 8px;
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.35;
            letter-spacing: -0.015em;
            min-height: 40px;
        }

        @media (min-width: 1024px) {
            .product-title {
                font-size: 16px;
            }
        }

        .product-title a {
            color: inherit;
            text-decoration: none;
        }

        .product-title a:hover {
            color: #000;
        }

        .cart-btn {
            flex-shrink: 0;
            margin-top: 2px;
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 9999px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: background-color 0.2s ease;
        }

        .cart-btn:hover {
            background: #f9fafb;
        }

        .cart-icon {
            width: 1rem;
            height: 1rem;
            color: #111827;
        }

        .empty-state {
            margin-top: 18px;
            padding: 60px 20px;
            border: 1px dashed #d1d5db;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(248,250,252,0.95));
            text-align: center;
        }

        .empty-title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin: 0;
            letter-spacing: -0.03em;
        }

        .empty-text {
            margin-top: 10px;
            font-size: 15px;
            color: #6b7280;
            font-weight: 500;
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

    <div class="clothing-page" x-data="clothingPageData()" x-init="init()">
        <div class="collection-shell">
            <div class="collection-header">
                <h1 class="collection-title" x-text="headingTitle"></h1>

                <div class="collection-subtitle">
                    Explore elegant pieces from our premium women collection.
                </div>

                <div class="collection-count">
                    <span x-text="filteredProducts.length.toLocaleString()"></span> items
                </div>
            </div>

            <div class="category-strip-wrap">
                <div class="category-strip-row">
                    <button
                        type="button"
                        class="category-scroll-btn"
                        x-show="showArrows"
                        x-cloak
                        @click="scrollCategories('left')"
                        :disabled="!canScrollLeft"
                    >
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 19-7-7 7-7"/>
                        </svg>
                    </button>

                    <div class="category-strip" x-ref="categoryStrip" @scroll="updateScrollButtons()">
                        @foreach ($categories as $category)
                            <button
                                type="button"
                                class="category-chip"
                                :class="{ 'active': activeCategory === {{ $category->id }} }"
                                @click="changeCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            >
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>

                    <button
                        type="button"
                        class="category-scroll-btn"
                        x-show="showArrows"
                        x-cloak
                        @click="scrollCategories('right')"
                        :disabled="!canScrollRight"
                    >
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 5 7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="products-fade" :class="{ 'loading': isChanging }">
                <template x-if="filteredProducts.length > 0">
                    <div class="products-grid">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div class="product-card">
                                <a :href="product.product_url" class="product-image-link">
                                    <div class="product-image-frame">
                                        <img :src="product.image" :alt="product.name" class="product-image">
                                    </div>

                                    <template x-if="parseFloat(product.actual_price) > parseFloat(product.discounted_price)">
                                        <div class="discount-badge">
                                            <span x-text="'-' + formatDiscount(product.discount_percentage) + '%'"></span>
                                        </div>
                                    </template>

                                    <button
                                        type="button"
                                        class="wishlist-float-btn"
                                        :class="{ 'active': isInWishlist(product.id) }"
                                        @click.prevent="toggleWishlist(product)"
                                    >
                                        <svg
                                            class="wishlist-float-icon"
                                            :fill="isInWishlist(product.id) ? 'currentColor' : 'none'"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"/>
                                        </svg>
                                    </button>
                                </a>

                                <div class="product-meta">
                                    <div class="product-meta-row">
                                        <div class="product-meta-main">
                                            <div class="sale-price">
                                                PKR <span x-text="formatPrice(product.discounted_price)"></span>
                                            </div>

                                            <template x-if="parseFloat(product.actual_price) > parseFloat(product.discounted_price)">
                                                <div class="old-price">
                                                    PKR <span x-text="formatPrice(product.actual_price)"></span>
                                                </div>
                                            </template>

                                            <div class="product-title">
                                                <a :href="product.product_url" x-text="product.name"></a>
                                            </div>
                                        </div>

                                        <button
                                            type="button"
                                            class="cart-btn"
                                            @click="openQuickView(product)"
                                        >
                                            <svg class="cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.2 1.6a1 1 0 0 0 .8 1.4H19m-9 4a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="filteredProducts.length === 0">
                    <div class="empty-state">
                        <h2 class="empty-title">No products found</h2>
                        <div class="empty-text">
                            There are no products available in this category right now.
                        </div>
                    </div>
                </template>
            </div>
        </div>

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

        @include('users.partials.quick-view-modal')
    </div>

   <script>
    function clothingPageData() {
        return {
            activeCategory: {{ $firstCategory ? (int) $firstCategory->id : 'null' }},
            activeCategoryName: @json($firstCategory?->name ?? 'Women Ready To Wear'),
            isChanging: false,
            toastOpen: false,
            toastMessage: '',
            toastImage: '',
            canScrollLeft: false,
            canScrollRight: false,
            showArrows: false,
            products: @json($allProducts),
            wishlist: @json(array_map('intval', $wishlistIds)),

            quickView: {
                open: false,
                loading: false,
                product: null,
                activeImage: '',
                activeImageIndex: 0,
                qty: 1,
                selectedSize: '',
                sizes: {},
                currentStock: 0,
                canAddToBag: false,
            },

            init() {
                this.$nextTick(() => {
                    this.updateScrollButtons();

                    window.addEventListener('resize', () => {
                        this.updateScrollButtons();
                    });
                });
            },

            openQuickView(product) {
                this.quickView.open = true;
                this.quickView.loading = true;
                this.quickView.product = null;
                this.quickView.activeImage = '';
                this.quickView.activeImageIndex = 0;
                this.quickView.qty = 1;
                this.quickView.selectedSize = '';
                this.quickView.sizes = {};
                this.quickView.currentStock = 0;
                this.quickView.canAddToBag = false;

                document.body.style.overflow = 'hidden';

                setTimeout(() => {
                    this.quickView.product = product;
                    this.quickView.activeImageIndex = 0;
                    this.quickView.activeImage = (product.images && product.images.length) ? product.images[0] : (product.image || '');
                    this.quickView.sizes = product.sizes || {};
                    this.quickView.loading = false;
                }, 450);
            },

            closeQuickView() {
                this.quickView.open = false;
                this.quickView.loading = false;
                this.quickView.product = null;
                this.quickView.activeImage = '';
                this.quickView.activeImageIndex = 0;
                this.quickView.qty = 1;
                this.quickView.selectedSize = '';
                this.quickView.sizes = {};
                this.quickView.currentStock = 0;
                this.quickView.canAddToBag = false;

                document.body.style.overflow = '';
            },

            setQuickViewImage(index) {
                if (!this.quickView.product || !this.quickView.product.images || !this.quickView.product.images.length) return;
                this.quickView.activeImageIndex = index;
                this.quickView.activeImage = this.quickView.product.images[index];
            },

            nextQuickViewImage() {
                if (!this.quickView.product || !this.quickView.product.images || this.quickView.product.images.length <= 1) return;

                this.quickView.activeImageIndex =
                    (this.quickView.activeImageIndex + 1) % this.quickView.product.images.length;

                this.quickView.activeImage = this.quickView.product.images[this.quickView.activeImageIndex];
            },

            prevQuickViewImage() {
                if (!this.quickView.product || !this.quickView.product.images || this.quickView.product.images.length <= 1) return;

                this.quickView.activeImageIndex =
                    (this.quickView.activeImageIndex - 1 + this.quickView.product.images.length) % this.quickView.product.images.length;

                this.quickView.activeImage = this.quickView.product.images[this.quickView.activeImageIndex];
            },

            selectQuickViewSize(size) {
                const stock = parseInt(this.quickView.sizes[size] || 0);
                if (stock <= 0) return;

                this.quickView.selectedSize = size;
                this.quickView.currentStock = stock;

                if (this.quickView.qty > stock) {
                    this.quickView.qty = stock;
                }

                if (this.quickView.qty < 1) {
                    this.quickView.qty = 1;
                }

                this.quickView.canAddToBag =
                    this.quickView.selectedSize !== '' &&
                    this.quickView.currentStock > 0 &&
                    this.quickView.qty >= 1 &&
                    this.quickView.qty <= this.quickView.currentStock;
            },

            increaseQuickViewQty() {
                if (!this.quickView.selectedSize) return;

                if (this.quickView.qty < this.quickView.currentStock) {
                    this.quickView.qty++;
                }

                this.quickView.canAddToBag =
                    this.quickView.selectedSize !== '' &&
                    this.quickView.currentStock > 0 &&
                    this.quickView.qty >= 1 &&
                    this.quickView.qty <= this.quickView.currentStock;
            },

            decreaseQuickViewQty() {
                if (this.quickView.qty > 1) {
                    this.quickView.qty--;
                }

                this.quickView.canAddToBag =
                    this.quickView.selectedSize !== '' &&
                    this.quickView.currentStock > 0 &&
                    this.quickView.qty >= 1 &&
                    this.quickView.qty <= this.quickView.currentStock;
            },

            addQuickViewToCart() {
                if (!this.quickView.canAddToBag) return;

                alert(
                    'Added to bag:\n' +
                    'Product: ' + this.quickView.product.name + '\n' +
                    'Size: ' + this.quickView.selectedSize + '\n' +
                    'Qty: ' + this.quickView.qty
                );

                this.closeQuickView();
            },

            changeCategory(categoryId, categoryName) {
                if (this.activeCategory === categoryId) return;

                this.isChanging = true;

                setTimeout(() => {
                    this.activeCategory = categoryId;
                    this.activeCategoryName = categoryName;
                    this.isChanging = false;
                }, 140);
            },

            get filteredProducts() {
                if (!this.activeCategory) {
                    return this.products;
                }

                return this.products.filter(product => parseInt(product.category_id) === parseInt(this.activeCategory));
            },

            get headingTitle() {
                if (!this.activeCategoryName) {
                    return 'Women Ready To Wear';
                }

                const text = this.activeCategoryName;
                return text.charAt(0).toUpperCase() + text.slice(1);
            },

            formatPrice(value) {
                return new Intl.NumberFormat('en-PK', {
                    maximumFractionDigits: 0
                }).format(value);
            },

            formatDiscount(value) {
                const number = parseFloat(value || 0);
                return number % 1 === 0
                    ? number.toFixed(0)
                    : number.toFixed(2).replace(/\.?0+$/, '');
            },

            scrollCategories(direction) {
                const strip = this.$refs.categoryStrip;
                if (!strip) return;

                const amount = 240;

                strip.scrollBy({
                    left: direction === 'right' ? amount : -amount,
                    behavior: 'smooth'
                });

                setTimeout(() => {
                    this.updateScrollButtons();
                }, 260);
            },

            updateScrollButtons() {
                const strip = this.$refs.categoryStrip;
                if (!strip) return;

                this.showArrows = strip.scrollWidth > strip.clientWidth + 8;
                this.canScrollLeft = strip.scrollLeft > 5;
                this.canScrollRight = strip.scrollLeft + strip.clientWidth < strip.scrollWidth - 5;
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

            async toggleWishlist(product) {
                const productId = parseInt(product.id);
                const alreadyInWishlist = this.isInWishlist(productId);
                const url = alreadyInWishlist ? product.wishlist_destroy_url : product.wishlist_store_url;
                const method = alreadyInWishlist ? 'DELETE' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
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

                    if (data.action === 'added') {
                        if (!this.isInWishlist(productId)) {
                            this.wishlist.push(productId);
                        }
                        this.showToast(data.message || 'Item added to favourites', product.image);
                    } else if (data.action === 'removed') {
                        this.wishlist = this.wishlist.filter(id => id !== productId);
                        this.showToast(data.message || 'Item removed from favourites', product.image);
                    }
                } catch (error) {
                    console.error('Wishlist AJAX error:', error);
                    this.showToast('Something went wrong', product.image);
                }
            }
        }
    }
</script>
@endsection


