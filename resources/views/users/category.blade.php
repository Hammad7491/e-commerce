@extends('users.layouts.app')

@section('title', $category->name)

@section('content')
    <div class="p-6 lg:p-8">
        <div class="mb-8">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Women / Clothing</p>
            <h1 class="mt-2 text-4xl font-extrabold text-gray-900">{{ $category->name }}</h1>
            <p class="mt-2 text-gray-600 text-lg">
                Explore all products in {{ $category->name }} collection.
            </p>
        </div>

        @if ($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    @php
                        $firstMedia = $product->firstMedia;
                        $hasDiscount = (float) $product->actual_price > (float) $product->discounted_price;
                    @endphp

                    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition duration-300">
                        <div class="relative bg-gray-100">
                            @if ($firstMedia && $firstMedia->file_type === 'image')
                                <img
                                    src="{{ asset('storage/' . $firstMedia->file_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-80 object-cover"
                                >
                            @elseif ($firstMedia && $firstMedia->file_type === 'video')
                                <div class="w-full h-80 flex items-center justify-center bg-black text-white font-semibold">
                                    Video Preview
                                </div>
                            @else
                                <div class="w-full h-80 flex items-center justify-center text-gray-400 bg-gray-50">
                                    No Media
                                </div>
                            @endif

                            @if ($hasDiscount)
                                <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold shadow-sm">
                                    -{{ rtrim(rtrim(number_format((float) $product->discount_percentage, 2), '0'), '.') }}%
                                </span>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3 class="text-xl font-bold text-gray-900 leading-8 min-h-[64px]">
                                {{ $product->name }}
                            </h3>

                            <div class="mt-4">
                                <p class="text-3xl font-extrabold text-gray-900">
                                    PKR {{ number_format((float) $product->discounted_price, 0) }}
                                </p>

                                @if ($hasDiscount)
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-lg text-gray-400 line-through">
                                            PKR {{ number_format((float) $product->actual_price, 0) }}
                                        </span>
                                        <span class="text-red-500 text-sm font-bold">
                                            -{{ rtrim(rtrim(number_format((float) $product->discount_percentage, 2), '0'), '.') }}%
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-5">
                                <a href="{{ url('/product/' . $product->slug) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-black text-white text-sm font-semibold hover:bg-gray-800 transition">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center text-gray-500 text-xl">
                No products found in {{ $category->name }}.
            </div>
        @endif
    </div>
@endsection