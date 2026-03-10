<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'product';

        $slug = $base;
        $i = 2;

        while (
            Product::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function index()
    {
        $products = Product::with(['category', 'firstMedia'])
            ->latest()
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->loadMissing(['category', 'media']);

        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::latest()->get();

        return view('admin.products.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::latest()->get();

        return view('admin.products.create', compact('categories', 'product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'actual_price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['required', 'numeric', 'min:0'],

            'small_stock' => ['nullable', 'integer', 'min:0'],
            'medium_stock' => ['nullable', 'integer', 'min:0'],
            'large_stock' => ['nullable', 'integer', 'min:0'],
            'xl_stock' => ['nullable', 'integer', 'min:0'],

            'bottom_style' => ['nullable', 'string', 'max:255'],
            'color_type' => ['nullable', 'string', 'max:255'],
            'product_code' => ['nullable', 'string', 'max:255'],
            'lining_attached' => ['nullable', 'string', 'max:255'],
            'number_of_pieces' => ['nullable', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:255'],
            'shirt_fabric' => ['nullable', 'string', 'max:255'],
            'top_style' => ['nullable', 'string', 'max:255'],
            'trouser_fabrics' => ['nullable', 'string', 'max:255'],

            'media' => ['nullable', 'array', 'max:8'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv', 'max:102400'],
        ]);

        if ((float) $validated['discounted_price'] > (float) $validated['actual_price']) {
            return back()
                ->withErrors(['discounted_price' => 'Discounted price must be less than or equal to actual price.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $actualPrice = (float) $validated['actual_price'];
            $discountedPrice = (float) $validated['discounted_price'];

            $discountPercentage = 0;
            if ($actualPrice > 0 && $discountedPrice < $actualPrice) {
                $discountPercentage = (($actualPrice - $discountedPrice) / $actualPrice) * 100;
            }

            $slug = $this->generateUniqueSlug($validated['name']);

            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'actual_price' => $actualPrice,
                'discounted_price' => $discountedPrice,
                'discount_percentage' => round($discountPercentage, 2),

                'small_stock' => $request->input('small_stock', 0),
                'medium_stock' => $request->input('medium_stock', 0),
                'large_stock' => $request->input('large_stock', 0),
                'xl_stock' => $request->input('xl_stock', 0),

                'bottom_style' => $request->input('bottom_style'),
                'color_type' => $request->input('color_type'),
                'product_code' => $request->input('product_code'),
                'lining_attached' => $request->input('lining_attached'),
                'number_of_pieces' => $request->input('number_of_pieces'),
                'product_type' => $request->input('product_type'),
                'season' => $request->input('season'),
                'shirt_fabric' => $request->input('shirt_fabric'),
                'top_style' => $request->input('top_style'),
                'trouser_fabrics' => $request->input('trouser_fabrics'),
            ]);

            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $index => $file) {
                    $mimeType = $file->getMimeType();
                    $fileType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

                    $path = $file->store('products', 'public');

                    ProductMedia::create([
                        'product_id' => $product->id,
                        'file_path' => $path,
                        'file_type' => $fileType,
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Something went wrong while saving the product.'])
                ->withInput();
        }
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'actual_price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['required', 'numeric', 'min:0'],

            'small_stock' => ['nullable', 'integer', 'min:0'],
            'medium_stock' => ['nullable', 'integer', 'min:0'],
            'large_stock' => ['nullable', 'integer', 'min:0'],
            'xl_stock' => ['nullable', 'integer', 'min:0'],

            'bottom_style' => ['nullable', 'string', 'max:255'],
            'color_type' => ['nullable', 'string', 'max:255'],
            'product_code' => ['nullable', 'string', 'max:255'],
            'lining_attached' => ['nullable', 'string', 'max:255'],
            'number_of_pieces' => ['nullable', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'max:255'],
            'season' => ['nullable', 'string', 'max:255'],
            'shirt_fabric' => ['nullable', 'string', 'max:255'],
            'top_style' => ['nullable', 'string', 'max:255'],
            'trouser_fabrics' => ['nullable', 'string', 'max:255'],

            'media' => ['nullable', 'array', 'max:8'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv', 'max:102400'],
        ]);

        if ((float) $validated['discounted_price'] > (float) $validated['actual_price']) {
            return back()
                ->withErrors(['discounted_price' => 'Discounted price must be less than or equal to actual price.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $actualPrice = (float) $validated['actual_price'];
            $discountedPrice = (float) $validated['discounted_price'];

            $discountPercentage = 0;
            if ($actualPrice > 0 && $discountedPrice < $actualPrice) {
                $discountPercentage = (($actualPrice - $discountedPrice) / $actualPrice) * 100;
            }

            $slug = $this->generateUniqueSlug($validated['name'], (int) $product->id);

            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'actual_price' => $actualPrice,
                'discounted_price' => $discountedPrice,
                'discount_percentage' => round($discountPercentage, 2),

                'small_stock' => $request->input('small_stock', 0),
                'medium_stock' => $request->input('medium_stock', 0),
                'large_stock' => $request->input('large_stock', 0),
                'xl_stock' => $request->input('xl_stock', 0),

                'bottom_style' => $request->input('bottom_style'),
                'color_type' => $request->input('color_type'),
                'product_code' => $request->input('product_code'),
                'lining_attached' => $request->input('lining_attached'),
                'number_of_pieces' => $request->input('number_of_pieces'),
                'product_type' => $request->input('product_type'),
                'season' => $request->input('season'),
                'shirt_fabric' => $request->input('shirt_fabric'),
                'top_style' => $request->input('top_style'),
                'trouser_fabrics' => $request->input('trouser_fabrics'),
            ]);

            if ($request->hasFile('media')) {
                ProductMedia::where('product_id', $product->id)->delete();

                foreach ($request->file('media') as $index => $file) {
                    $mimeType = $file->getMimeType();
                    $fileType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

                    $path = $file->store('products', 'public');

                    ProductMedia::create([
                        'product_id' => $product->id,
                        'file_path' => $path,
                        'file_type' => $fileType,
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Something went wrong while updating the product.'])
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'No products selected.');
        }

        Product::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Selected products deleted successfully.');
    }
}