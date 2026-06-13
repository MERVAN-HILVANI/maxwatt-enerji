<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.images')
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->discounted_price * $item->quantity;
        });

        $totalKdv = $cartItems->sum(function($item) {
            return ($item->product->kdv_dahil_fiyat - $item->product->discounted_price) * $item->quantity;
        });

        return view('shop.cart', compact('cartItems', 'total', 'totalKdv'));
    }

    public function add(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login')->with('error', 'Sepete eklemek için giriş yapmalısınız.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return back()->with('success', 'Ürün sepete eklendi.');
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->quantity < 1) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $request->quantity]);
        }

        return back()->with('success', 'Sepet güncellendi.');
    }

    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Ürün sepetten çıkarıldı.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Sepet temizlendi.');
    }
}
