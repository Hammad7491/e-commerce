@extends('admin.layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ isset($product) ? 'Edit Product' : 'Create Product' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    {{ isset($product) ? 'Update product information, media, stock, pricing, and details.' : 'Add a new product with media, stock, pricing, and product details.' }}
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                Back
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Basic Information</h2>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select name="category_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', isset($product) ? $product->category_id : null) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name</label>
                        <input type="text" id="product_name" name="name" value="{{ old('name', $product->name ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="Enter product name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                        <input type="text" id="product_slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 outline-none focus:border-black"
                            placeholder="Auto generated" readonly>
                        <p class="mt-2 text-xs text-gray-500">Used in product URL example fancy-summer-dress</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea
                        name="description"
                        rows="5"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black resize-none"
                        placeholder="Enter product description">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Pricing</h2>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Actual Price</label>
                        <input type="number" step="0.01" min="0" name="actual_price" id="actual_price" value="{{ old('actual_price', $product->actual_price ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="Enter actual price">
                        @error('actual_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Discounted Price</label>
                        <input type="number" step="0.01" min="0" name="discounted_price" id="discounted_price" value="{{ old('discounted_price', $product->discounted_price ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="Enter discounted price">
                        @error('discounted_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Off Percentage</label>
                        <input type="text" id="discount_percentage_preview"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 outline-none"
                            placeholder="Auto calculated"
                            readonly>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Media Upload</h2>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Images / Videos
                    </label>
                    <input type="file" name="media[]" multiple accept="image/*,video/*"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black bg-white">
                    <p class="mt-2 text-sm text-gray-500">
                        You can upload up to 8 files total. Images and videos can be combined. Max 100MB per file
                        @if (isset($product) && $product->media->count())
                            <br><span class="text-gray-600">Uploading new files will replace existing media.</span>
                        @endif
                    </p>

                    @error('media')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @error('media.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Size Stocks</h2>

                <div class="grid md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Small S Stock</label>
                        <input type="number" min="0" name="small_stock" value="{{ old('small_stock', $product->small_stock ?? 0) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                        @error('small_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Medium M Stock</label>
                        <input type="number" min="0" name="medium_stock" value="{{ old('medium_stock', $product->medium_stock ?? 0) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                        @error('medium_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Large L Stock</label>
                        <input type="number" min="0" name="large_stock" value="{{ old('large_stock', $product->large_stock ?? 0) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                        @error('large_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">XL Stock</label>
                        <input type="number" min="0" name="xl_stock" value="{{ old('xl_stock', $product->xl_stock ?? 0) }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                        @error('xl_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Product Details</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bottom Style</label>
                        <input type="text" name="bottom_style" value="{{ old('bottom_style', $product->bottom_style ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Color Type</label>
                        <input type="text" name="color_type" value="{{ old('color_type', $product->color_type ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product ID</label>
                        <input type="text" name="product_code" value="{{ old('product_code', $product->product_code ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="Example ZKP1107">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lining Attached</label>
                        <input type="text" name="lining_attached" value="{{ old('lining_attached', $product->lining_attached ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Number Of Pieces</label>
                        <input type="text" name="number_of_pieces" value="{{ old('number_of_pieces', $product->number_of_pieces ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                            placeholder="Example 2 piece top and bottom">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Type</label>
                        <input type="text" name="product_type" value="{{ old('product_type', $product->product_type ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Season</label>
                        <input type="text" name="season" value="{{ old('season', $product->season ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Shirt Fabric</label>
                        <input type="text" name="shirt_fabric" value="{{ old('shirt_fabric', $product->shirt_fabric ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Top Style</label>
                        <input type="text" name="top_style" value="{{ old('top_style', $product->top_style ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Trouser Fabrics</label>
                        <input type="text" name="trouser_fabrics" value="{{ old('trouser_fabrics', $product->trouser_fabrics ?? '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition">
                    {{ isset($product) ? 'Update Product' : 'Save Product' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const nameInput = document.getElementById('product_name');
            const slugInput = document.getElementById('product_slug');
            const actualPriceInput = document.getElementById('actual_price');
            const discountedPriceInput = document.getElementById('discounted_price');
            const discountPreviewInput = document.getElementById('discount_percentage_preview');

            function toSlug(text) {
                return (text || '')
                    .toString()
                    .trim()
                    .toLowerCase()
                    .replace(/['"]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            function updateDiscountPreview() {
                const actual = parseFloat(actualPriceInput.value || 0);
                const discounted = parseFloat(discountedPriceInput.value || 0);

                if (actual > 0 && discounted >= 0 && discounted < actual) {
                    const percent = ((actual - discounted) / actual) * 100;
                    discountPreviewInput.value = percent.toFixed(2) + '%';
                } else if (actual > 0 && discounted === actual) {
                    discountPreviewInput.value = '0.00%';
                } else {
                    discountPreviewInput.value = '';
                }
            }

            actualPriceInput?.addEventListener('input', updateDiscountPreview);
            discountedPriceInput?.addEventListener('input', updateDiscountPreview);
            updateDiscountPreview();

            function updateSlugPreview() {
                if (!slugInput || !nameInput) return;
                slugInput.value = toSlug(nameInput.value);
            }

            nameInput?.addEventListener('input', updateSlugPreview);
            updateSlugPreview();
        </script>
    @endpush
@endsection