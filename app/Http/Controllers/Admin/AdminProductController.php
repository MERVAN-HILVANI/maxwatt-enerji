<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with('parent')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->user_id = Auth::guard('admin')->id();
        $product->title = $request->title;
        $product->keywords = $request->keywords;
        $product->description = $request->description;
        $product->detail = $request->detail;
        $product->price = $request->price;
        $product->stock = $request->stock ?? 0;
        $product->minstock = $request->minstock ?? 0;
        $product->discount = $request->discount ?? 0;
        $product->kdv = $request->kdv ?? 18;
        $product->garanti = $request->garanti ?? 1;
        $product->status = $request->status ?? 0;
        $product->save();

        if ($request->hasFile('images')) {
            $primaryIndex = $request->primary_image_index ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'order' => $index,
                ]);
                if ($index == $primaryIndex) {
                    $product->image = $path;
                }
            }
            $product->save();
        }

        return redirect()->route('admin.product.index')
            ->with('success', 'Ürün başarıyla eklendi.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'images');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::with('parent')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->category_id = $request->category_id;
        $product->user_id = Auth::guard('admin')->id();
        $product->title = $request->title;
        $product->keywords = $request->keywords;
        $product->description = $request->description;
        $product->detail = $request->detail;
        $product->price = $request->price;
        $product->stock = $request->stock ?? 0;
        $product->minstock = $request->minstock ?? 0;
        $product->discount = $request->discount ?? 0;
        $product->kdv = $request->kdv ?? 18;
        $product->garanti = $request->garanti ?? 1;
        $product->status = $request->status ?? 0;

        if ($request->primary_image_id) {
            $product->image = $request->primary_image_id;
        }

        $product->save();

        return redirect()->route('admin.product.index')
            ->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate(['image' => 'required|image|max:2048']);

        $path = $request->file('image')->store('products', 'public');

        $img = ProductImage::create([
            'product_id' => $product->id,
            'image' => $path,
            'order' => $product->images()->max('order') + 1,
        ]);

        if (!$product->image) {
            $product->image = $path;
            $product->save();
        }

        return response()->json([
            'id' => $img->id,
            'url' => asset('storage/' . $path),
            'path' => $path,
        ]);
    }

    public function destroyImage(ProductImage $image)
    {
        $product = Product::find($image->product_id);

        if ($product && $product->image == $image->image) {
            $remaining = $product->images()->where('id', '!=', $image->id)->first();
            $product->image = $remaining ? $remaining->image : null;
            $product->save();
        }

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
        }
        $product->delete();
        return redirect()->route('admin.product.index')
            ->with('success', 'Ürün başarıyla silindi.');
    }
}
