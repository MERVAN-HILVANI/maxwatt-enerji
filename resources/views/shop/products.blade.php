@extends('layouts.shop')

@section('title') Ürünler @endsection

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    @isset($category)
                        <li class="breadcrumb-item active">{{ $category->name }}</li>
                    @else
                        <li class="breadcrumb-item active">Tüm Ürünler</li>
                    @endisset
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            {{-- FİLTRE --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtrele</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('shop.products') }}" method="GET">
                            <h6 style="font-size:13px; font-weight:bold;">Kategoriler</h6>
                            @foreach($categories as $cat)
                                <div class="form-check">
                                    <input type="radio" name="category" value="{{ $cat->id }}"
                                           class="form-check-input" id="cat_{{ $cat->id }}"
                                        {{ request('category') == $cat->id ? 'checked' : '' }}>
                                    <label for="cat_{{ $cat->id }}" class="form-check-label" style="font-size:13px;">
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            @endforeach

                            <hr>
                            <h6 style="font-size:13px; font-weight:bold;">Fiyat Aralığı</h6>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" class="form-control form-control-sm"
                                       placeholder="Min" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" class="form-control form-control-sm"
                                       placeholder="Max" value="{{ request('max_price') }}">
                            </div>

                            <hr>
                            <h6 style="font-size:13px; font-weight:bold;">Sırala</h6>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>En Yeni</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Fiyat (Artan)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Fiyat (Azalan)</option>
                            </select>

                            <button type="submit" class="btn w-100 mt-3 text-white" style="background:#1E3A5F; font-size:13px;">
                                <i class="bi bi-search"></i> Filtrele
                            </button>
                            <a href="{{ route('shop.products') }}" class="btn btn-outline-secondary w-100 mt-2" style="font-size:13px;">
                                Filtreyi Temizle
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ÜRÜNLER --}}
            <div class="col-md-9">
                @isset($q)
                    <h5 style="color:#1E3A5F;">"{{ $q }}" için arama sonuçları — {{ $products->total() }} ürün</h5>
                @endisset

                @isset($category)
                    <h5 style="color:#1E3A5F;">{{ $category->name }} — {{ $products->total() }} ürün</h5>
                @endisset

                <div class="row g-3">
                    @forelse($products as $product)
                        <div class="col-md-4 col-6">
                            <div class="product-card position-relative">
                                @if($product->discount > 0)
                                    <div class="discount-badge">-%{{ $product->discount }}</div>
                                @endif
                                @auth
                                    <form action="{{ route('favorite.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="favorite-btn">
                                            <i class="bi bi-heart{{ $product->favorites()->where('user_id', Auth::id())->exists() ? '-fill' : '' }}" style="color:red;"></i>
                                        </button>
                                    </form>
                                @endauth
                                <a href="{{ route('shop.product.detail', $product->id) }}">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                                    @else
                                        <div style="height:200px; background:#f0f4f8; display:flex; align-items:center; justify-content:center;">
                                            <i class="bi bi-image" style="font-size:40px; color:#ccc;"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="card-body">
                                    <a href="{{ route('shop.product.detail', $product->id) }}" class="text-decoration-none">
                                        <p style="font-size:13px; color:#333; margin-bottom:5px; height:40px; overflow:hidden;">{{ $product->title }}</p>
                                    </a>
                                    <div class="stars mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                                        @endfor
                                        <span style="font-size:12px; color:#999;">({{ $product->reviews->count() }})</span>
                                    </div>
                                    @if($product->discount > 0)
                                        <div class="old-price">{{ number_format($product->price, 2) }} ₺</div>
                                        <div class="price">{{ number_format($product->discounted_price, 2) }} ₺</div>
                                    @else
                                        <div class="price">{{ number_format($product->price, 2) }} ₺</div>
                                    @endif
                                    <div class="kdv-price">KDV Dahil: {{ number_format($product->kdv_dahil_fiyat, 2) }} ₺</div>
                                    <div style="font-size:11px; color:#999;">{{ $product->garanti }} Yıl Garanti</div>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn-add-cart">
                                            <i class="bi bi-cart-plus"></i> Sepete Ekle
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-search" style="font-size:50px; color:#ccc;"></i>
                            <p class="mt-3 text-muted">Ürün bulunamadı.</p>
                        </div>
                    @endforelse
                </div>

                {{-- SAYFALAMA --}}
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
