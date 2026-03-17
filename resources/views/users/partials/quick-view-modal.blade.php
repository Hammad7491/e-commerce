<div
    x-cloak
    x-show="quickView.open"
    x-transition.opacity
    @keydown.escape.window="closeQuickView()"
    class="fixed inset-0 z-[9999]"
>
    <div
        class="absolute inset-0 bg-black/55"
        @click="closeQuickView()"
    ></div>

    <style>
        .qv-scroll-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .qv-scroll-hide::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div class="absolute inset-0 overflow-y-auto qv-scroll-hide">
        <div class="min-h-full flex items-center justify-center p-3 md:p-5">
            <div
                class="relative w-full max-w-[1100px] rounded-[24px] bg-white shadow-2xl overflow-hidden"
                @click.stop
            >
                <button
                    type="button"
                    class="absolute top-4 right-4 z-30 inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/95 border border-gray-200 text-gray-700 hover:bg-white transition"
                    @click="closeQuickView()"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Loading Skeleton --}}
                <template x-if="quickView.loading">
                    <div class="p-5 md:p-7 animate-pulse">
                        <div class="space-y-3">
                            <div class="h-8 w-32 rounded bg-gray-200"></div>
                            <div class="h-5 w-48 rounded bg-gray-200"></div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-[1.38fr_1fr] gap-5">
                            <div class="rounded-[18px] bg-gray-200 aspect-[4/5] w-full"></div>

                            <div class="space-y-4">
                                <div class="h-8 w-44 rounded bg-gray-200"></div>
                                <div class="h-5 w-28 rounded bg-gray-200"></div>
                                <div class="h-10 w-56 rounded bg-gray-200"></div>
                                <div class="h-6 w-24 rounded bg-gray-200"></div>
                                <div class="flex gap-3">
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                </div>
                                <div class="h-6 w-24 rounded bg-gray-200"></div>
                                <div class="flex gap-3">
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                    <div class="h-12 w-12 rounded-xl bg-gray-200"></div>
                                </div>
                                <div class="h-48 w-full rounded-2xl bg-gray-200"></div>
                                <div class="h-24 w-full rounded-2xl bg-gray-200"></div>
                                <div class="h-14 w-full rounded-xl bg-gray-200"></div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Loaded Content --}}
                <template x-if="!quickView.loading && quickView.product">
                    <div class="grid grid-cols-1 lg:grid-cols-[1.38fr_1fr] min-h-[650px] max-h-[86vh]">
                        {{-- Left side fixed image --}}
                        <div class="relative bg-[#f7f7f7] lg:sticky lg:top-0 self-start h-full">
                            <div class="h-full min-h-[420px] lg:min-h-[86vh] max-h-[86vh] overflow-hidden">
                                <template x-if="quickView.activeImage">
                                    <img
                                        :src="quickView.activeImage"
                                        :alt="quickView.product.name"
                                        class="w-full h-full object-cover"
                                    >
                                </template>
                            </div>

                            <template x-if="quickView.product.images && quickView.product.images.length > 1">
                                <div class="absolute left-4 right-4 bottom-4 flex gap-2 overflow-x-auto qv-scroll-hide">
                                    <template x-for="(img, index) in quickView.product.images" :key="index">
                                        <button
                                            type="button"
                                            class="shrink-0 w-16 h-20 rounded-xl overflow-hidden border-2 bg-white"
                                            :class="quickView.activeImage === img ? 'border-black' : 'border-white/80'"
                                            @click="quickView.activeImage = img"
                                        >
                                            <img :src="img" alt="" class="w-full h-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Right side scrollable content --}}
                        <div class="max-h-[86vh] overflow-y-auto qv-scroll-hide bg-white">
                            <div class="p-5 md:p-6 lg:p-7 pr-5 md:pr-6 lg:pr-7">
                                <div class="pr-12">
                                    <h2
                                        class="text-[28px] md:text-[32px] font-bold tracking-[-0.03em] text-gray-900 leading-none capitalize"
                                        x-text="quickView.product.name"
                                    ></h2>

                                    <template x-if="quickView.product.category_name">
                                        <div
                                            class="mt-3 text-[15px] md:text-[16px] text-gray-500 font-medium"
                                            x-text="quickView.product.category_name"
                                        ></div>
                                    </template>
                                </div>

                                <div class="mt-5 flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-[36px] md:text-[40px] font-bold tracking-[-0.04em] text-gray-900 leading-none">
                                            PKR <span x-text="formatPrice(quickView.product.discounted_price)"></span>
                                        </div>

                                        <template x-if="parseFloat(quickView.product.actual_price) > parseFloat(quickView.product.discounted_price)">
                                            <div class="mt-3 flex items-center gap-3 flex-wrap">
                                                <div class="text-[16px] text-gray-500 line-through font-medium">
                                                    PKR <span x-text="formatPrice(quickView.product.actual_price)"></span>
                                                </div>
                                                <div class="text-[16px] font-bold text-red-500">
                                                    -<span x-text="formatDiscount(quickView.product.discount_percentage)"></span>%
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 transition"
                                        :class="{ 'text-red-500 border-red-200 bg-red-50': isInWishlist(quickView.product.id) }"
                                        @click="toggleWishlist(quickView.product)"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            :fill="isInWishlist(quickView.product.id) ? 'currentColor' : 'none'"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M4.318 6.318a4.5 4.5 0 0 0 0 6.364L12 20.364l7.682-7.682a4.5 4.5 0 0 0-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 0 0-6.364 0z"/>
                                        </svg>
                                    </button>
                                </div>

                                <template x-if="quickView.currentStock > 0 && quickView.selectedSize">
                                    <div class="mt-4 inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-[13px] font-semibold text-red-500">
                                        <span x-text="quickView.currentStock"></span>&nbsp;left in stock
                                    </div>
                                </template>

                                {{-- Size first --}}
                                <div class="mt-8">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-[16px] font-bold text-gray-900">Size</h3>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-3">
                                        <template x-for="(stock, sizeName) in quickView.sizes" :key="sizeName">
                                            <button
                                                type="button"
                                                class="relative min-w-[54px] h-[48px] px-4 rounded-xl border text-[17px] font-semibold transition"
                                                :class="[
                                                    quickView.selectedSize === sizeName ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-300',
                                                    parseInt(stock) <= 0 ? 'opacity-40 cursor-not-allowed bg-gray-100' : 'hover:border-black'
                                                ]"
                                                @click="selectQuickViewSize(sizeName)"
                                                :disabled="parseInt(stock) <= 0"
                                            >
                                                <span x-text="sizeName"></span>

                                                <template x-if="parseInt(stock) > 0 && parseInt(stock) <= 3">
                                                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 rounded-full bg-red-50 px-2 py-[2px] text-[11px] font-semibold text-red-500 border border-red-100 whitespace-nowrap">
                                                        <span x-text="stock"></span> left
                                                    </span>
                                                </template>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Qty after size --}}
                                <div class="mt-10">
                                    <h3 class="text-[16px] font-bold text-gray-900">Quantity</h3>

                                    <div class="mt-3 flex items-center gap-3">
                                        <button
                                            type="button"
                                            class="w-12 h-12 rounded-xl border border-gray-300 bg-gray-50 text-gray-700 text-[28px] leading-none hover:bg-gray-100 transition disabled:opacity-40"
                                            @click="decreaseQuickViewQty()"
                                            :disabled="quickView.qty <= 1"
                                        >−</button>

                                        <div class="min-w-[28px] text-center text-[22px] font-semibold text-gray-900" x-text="quickView.qty"></div>

                                        <button
                                            type="button"
                                            class="w-12 h-12 rounded-xl border border-gray-300 bg-white text-gray-700 text-[28px] leading-none hover:bg-gray-50 transition disabled:opacity-40"
                                            @click="increaseQuickViewQty()"
                                            :disabled="!quickView.selectedSize || quickView.qty >= quickView.currentStock"
                                        >+</button>
                                    </div>
                                </div>

                                {{-- Product details after qty --}}
                                <div class="mt-10">
                                    <h3 class="text-[16px] font-bold text-gray-900">Product Details</h3>

                                    <div class="mt-4 overflow-hidden rounded-xl border border-gray-200">
                                        <template x-if="quickView.product.details && Object.keys(quickView.product.details).length">
                                            <table class="w-full border-collapse">
                                                <tbody>
                                                    <template x-for="(value, label) in quickView.product.details" :key="label">
                                                        <tr x-show="value">
                                                            <td class="w-[42%] bg-gray-50 border-b border-r border-gray-200 px-4 py-3 text-[14px] font-semibold text-gray-700" x-text="label"></td>
                                                            <td class="border-b border-gray-200 px-4 py-3 text-[14px] font-medium text-gray-600" x-text="value"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </template>

                                        <template x-if="!quickView.product.details || !Object.keys(quickView.product.details).length">
                                            <div class="px-4 py-4 text-[14px] text-gray-500">No details available.</div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Description after details --}}
                                <template x-if="quickView.product.description">
                                    <div class="mt-10">
                                        <h3 class="text-[16px] font-bold text-gray-900">Additional Description:</h3>
                                        <div
                                            class="mt-3 text-[15px] leading-7 text-gray-600 whitespace-pre-line"
                                            x-text="quickView.product.description"
                                        ></div>
                                    </div>
                                </template>

                                {{-- Add to bag at bottom --}}
                                <button
                                    type="button"
                                    class="mt-10 w-full h-[56px] rounded-xl bg-black text-white text-[18px] font-bold transition hover:bg-gray-900 disabled:opacity-40 disabled:cursor-not-allowed"
                                    :disabled="!quickView.canAddToBag"
                                    @click="addQuickViewToCart()"
                                >
                                    <span x-show="!quickView.selectedSize">Select Size To Add</span>
                                    <span x-show="quickView.selectedSize && quickView.canAddToBag">Add To Bag</span>
                                    <span x-show="quickView.selectedSize && !quickView.canAddToBag && quickView.currentStock <= 0">Out Of Stock</span>
                                </button>

                                <div class="h-2"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>