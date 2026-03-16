@extends('users.layouts.app')

@section('title', 'Wishlist')

@section('content')
    <style>
        .wishlist-page {
            padding: 24px 16px 46px;
        }

        @media (min-width: 768px) {
            .wishlist-page {
                padding: 28px 20px 56px;
            }
        }

        @media (min-width: 1280px) {
            .wishlist-page {
                padding: 30px 24px 60px;
            }
        }

        .wishlist-header {
            margin-bottom: 28px;
        }

        .wishlist-title {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.1;
            color: #111827;
            letter-spacing: -0.03em;
            margin: 0;
        }

        .wishlist-subtitle {
            margin-top: 8px;
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
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            overflow: hidden;
            transition: 0.2s ease;
        }

        .wishlist-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(17, 24, 39, 0.07);
        }

        .wishlist-image-wrap {
            position: relative;
            background: #f7f7f7;
        }

        .wishlist-image {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            display: block;
        }

        .wishlist-remove-form {
            position: absolute;
            top: 12px;
            right: 12px;
            margin: 0;
        }

        .wishlist-remove-btn {
            width: 42px;
            height: 42px;
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.96);
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: 0.2s ease;
        }

        .wishlist-remove-btn:hover {
            background: #fff;
            transform: scale(1.04);
        }

        .wishlist-body {
            padding: 16px;
        }

        .wishlist-name {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            line-height: 1.25;
            margin: 0;
        }

        .wishlist-name a {
            color: inherit;
            text-decoration: none;
        }

        .wishlist-name a:hover {
            color: #000;
        }

        .wishlist-category {
            margin-top: 6px;
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .wishlist-price-row {
            margin-top: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .wishlist-price {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.02em;
        }

        .wishlist-old-price {
            font-size: 15px;
            color: #6b7280;
            text-decoration: line-through;
            font-weight: 500;
        }

        .wishlist-actions {
            margin-top: 16px;
            display: flex;
            gap: 10px;
        }

        .wishlist-view-btn,
        .wishlist-remove-text-btn {
            flex: 1;
            height: 46px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .wishlist-view-btn {
            background: #111827;
            color: #fff;
            border: 1px solid #111827;
        }

        .wishlist-view-btn:hover {
            background: #000;
        }

        .wishlist-remove-text-btn {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .wishlist-remove-text-btn:hover {
            background: #f9fafb;
        }

        .wishlist-empty {
            padding: 60px 20px;
            text-align: center;
            border: 1px dashed #d1d5db;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255,255,255,0.75), rgba(255,255,255,0.45));
        }

        .wishlist-empty-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .wishlist-empty-text {
            margin-top: 10px;
            color: #6b7280;
            font-size: 16px;
            font-weight: 500;
        }

        .wishlist-empty-btn {
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 24px;
            border-radius: 12px;
            background: #111827;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
        }

        .wishlist-empty-btn:hover {
            background: #000;
        }

        .wishlist-alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
            font-weight: 600;
        }
    </style>

    <div class="wishlist-page">
        <div class="wishlist-header">
            <h1 class="wishlist-title">My Wishlist</h1>
            <div class="wishlist-subtitle">
                Save your favorite items and view them anytime.
            </div>
        </div>

        @if (session('success'))
            <div class="wishlist-alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($products->count())
            <div class="wishlist-grid">
                @foreach ($products as $product)
                    @php
                        $firstMedia = $product->firstMedia;
                        $hasDiscount = (float) $product->actual_price > (float) $product->discounted_price;
                    @endphp

                    <div class="wishlist-card">
                        <div class="wishlist-image-wrap">
                            <a href="{{ route('users.product', $product) }}">
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

                            <form action="{{ route('users.wishlist.destroy', $product) }}" method="POST" class="wishlist-remove-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="wishlist-remove-btn" title="Remove from Wishlist">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="wishlist-body">
                            <h3 class="wishlist-name">
                                <a href="{{ route('users.product', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <div class="wishlist-category">
                                {{ $product->category?->name ?? 'Women Collection' }}
                            </div>

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

                            <div class="wishlist-actions">
                                <a href="{{ route('users.product', $product) }}" class="wishlist-view-btn">
                                    View Product
                                </a>

                                <form action="{{ route('users.wishlist.destroy', $product) }}" method="POST" style="flex:1; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="wishlist-remove-text-btn" style="width:100%;">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="wishlist-empty">
                <h2 class="wishlist-empty-title">Your wishlist is empty</h2>
                <div class="wishlist-empty-text">
                    Start adding products you love and they will appear here.
                </div>

                <a href="{{ route('home') }}" class="wishlist-empty-btn">
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
@endsection