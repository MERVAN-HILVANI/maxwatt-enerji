<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $featuredProducts = Product::where('status', 1)
            ->where('discount', '>', 0)
            ->with('images', 'reviews', 'favorites')
            ->take(8)
            ->get();
        $products = Product::where('status', 1)
            ->with('images', 'reviews', 'favorites')
            ->latest()
            ->take(8)
            ->get();

        return view('shop.home', compact('categories', 'featuredProducts', 'products'));
    }

    public function products(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        $query = Product::where('status', 1)->with('images', 'reviews');

        // Filtreleme
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sıralama
        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort == 'newest') {
            $query->latest();
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        return view('shop.products', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = Product::with('images', 'reviews.user', 'category', 'favorites')
            ->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->take(4)
            ->get();

        return view('shop.product-detail', compact('product', 'relatedProducts'));
    }

    public function category($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $products = Product::where('category_id', $id)
            ->where('status', 1)
            ->with('images', 'reviews')
            ->paginate(12);

        return view('shop.products', compact('products', 'categories', 'category'));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $categories = Category::where('status', 1)->get();
        $products = Product::where('status', 1)
            ->where(function($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('keywords', 'like', "%$q%");
            })
            ->with('images', 'reviews')
            ->paginate(12);

        return view('shop.products', compact('products', 'categories', 'q'));
    }

    public function addFavorite($id)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Favorilere eklemek için giriş yapmalısınız.');
        }

        $existing = Favorite::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Ürün favorilerden çıkarıldı.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $id,
        ]);

        return back()->with('success', 'Ürün favorilere eklendi.');
    }

    public function removeFavorite($id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->delete();

        return back()->with('success', 'Ürün favorilerden çıkarıldı.');
    }

    public function favorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product.images')
            ->get();

        return view('shop.favorites', compact('favorites'));
    }

    public function addReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $existing = Review::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Bu ürüne zaten yorum yaptınız.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Yorumunuz eklendi.');
    }
}
