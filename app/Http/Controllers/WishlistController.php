<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        $wishlistIds = session()->get('wishlist', []);

        $products = Product::with(['category', 'firstMedia'])
            ->whereIn('id', $wishlistIds)
            ->get()
            ->sortBy(function ($product) use ($wishlistIds) {
                return array_search($product->id, $wishlistIds);
            })
            ->values();

        return view('users.wishlist', compact('categories', 'products'));
    }

    public function store(Product $product)
    {
        $wishlist = session()->get('wishlist', []);

        if (!in_array($product->id, $wishlist)) {
            $wishlist[] = $product->id;
            session()->put('wishlist', $wishlist);
        }

        return back()->with('success', 'Product added to wishlist.');
    }

    public function destroy(Product $product)
    {
        $wishlist = session()->get('wishlist', []);

        $wishlist = array_values(array_filter($wishlist, function ($id) use ($product) {
            return (int) $id !== (int) $product->id;
        }));

        session()->put('wishlist', $wishlist);

        return back()->with('success', 'Product removed from wishlist.');
    }
}