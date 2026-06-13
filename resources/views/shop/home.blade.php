@extends('layouts.shop')

@section('title') Ana Sayfa @endsection

@section('content')

    {{-- BANNER --}}
    <div style="background: linear-gradient(135deg, #0A1628, #1E3A5F); color: white; padding: 60px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 style="font-size: 2.5rem; font-weight: bold;">Türkiye'nin Güvenilir<br><span style="color: #3DA8E8;">Solar Enerji</span> Mağazası</h1>
                    <p style="font-size: 1.1rem; opacity: 0.8; margin: 20px 0;">Güneş panelleri, inverterler, bataryalar ve daha fazlası. En kaliteli ürünler, en uygun fiyatlarla.</p>
                    <a href="{{ route('shop.products') }}" class="btn btn-lg" style="background: #3DA8E8; color: white; border-radius: 25px; padding: 12px 30px;">
                        <i class="bi bi-shop"></i> Alışverişe Başla
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/logo-şefaf.png') }}" style="max-height: 200px; opacity: 0.9;">
                </div>
            </div>
        </div>
    </div>

    {{-- ÖZELLİKLER --}}
    <div style="background: white; padding: 20px 0; border-bottom: 1px solid #eee;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 py-2">
                    <i class="bi bi-truck" style="font-size: 24px; color: #3DA8E8;"></i>
                    <p class="mb-0 mt-1" style="font-size: 13px; font-weight: bold;">Ücretsiz Kargo</p>
                    <p class="mb-0" style="font-size: 12px; color: #999;">500₺ üzeri siparişlerde</p>
                </div>
                <div class="col-md-3 col-6 py-2">
                    <i class="bi bi-shield-check" style="font-size: 24px; color: #3DA8E8;"></i>
                    <p class="mb-0 mt-1" style="font-size: 13px; font-weight: bold;">Güvenli Alışveriş</p>
                    <p class="mb-0" style="font-size: 12px; color: #999;">SSL ile güvende</p>
                </div>
                <div class="col-md-3 col-6 py-2">
                    <i class="bi bi-arrow-return-left" style="font-size: 24px; color: #3DA8E8;"></i>
                    <p class="mb-0 mt-1" style="font-size: 13px; font-weight: bold;">Kolay İade</p>
                    <p class="mb-0" style="font-size: 12px; color: #999;">14 gün içinde iade</p>
                </div>
                <div class="col-md-3 col-6 py-2">
                    <i class="bi bi-headset" style="font-size: 24px; color: #3DA8E8;"></i>
                    <p class="mb-0 mt-1" style="font-size: 13px; font-weight: bold;">7/24 Destek</p>
                    <p class="mb-0" style="font-size: 12px; color: #999;">Her zaman yanınızdayız</p>
                </div>
            </div>
        </div>
    </div>

    {{-- KATEGORİLER --}}
    <div class="container mt-4">
        <h4 style="color: #1E3A5F; font-weight: bold; margin-bottom: 20px;">
            <i class="bi bi-grid"></i> Kategoriler
        </h4>
        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-md-2 col-4">
                    <a href="{{ route('shop.category', $category->id) }}" class="text-decoration-none">
                        <div class="text-center p-3 rounded" style="background: white; border: 1px solid #eee; transition: 0.3s;" onmouseover="this.style.borderColor='#3DA8E8'" onmouseout="this.style.borderColor='#eee'">
                            <i class="bi bi-lightning-charge" style="font-size: 30px; color: #3DA8E8;"></i>
                            <p class="mb-0 mt-2" style="font-size: 12px; font-weight: bold; color: #1E3A5F;">{{ $category->name }}</p>
                            <p class="mb-0" style="font-size: 11px; color: #999;">{{ $category->products()->where('status', 1)->count() }} ürün</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ÖNE ÇIKAN ÜRÜNLER --}}
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: #1E3A5F; font-weight: bold;">
                <i class="bi bi-star"></i> Öne Çıkan Ürünler
            </h4>
            <a href="{{ route('shop.products') }}" style="color: #3DA8E8; text-decoration: none; font-size: 14px;">
                Tümünü Gör <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($featuredProducts as $product)
                <div class="col-md-3 col-6">
                    <div class="product-card position-relative">
                        @if($product->discount > 0)
                            <div class="discount-badge">-%{{ $product->discount }}</div>
                        @endif
                        @auth
                            <form action="{{ route('favorite.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="favorite-btn {{ $product->favorites()->where('user_id', Auth::id())->exists() ? 'active' : '' }}">
                                    <i class="bi bi-heart{{ $product->favorites()->where('user_id', Auth::id())->exists() ? '-fill' : '' }}" style="color: red;"></i>
                                </button>
                            </form>
                        @endauth
                        <a href="{{ route('shop.product.detail', $product->id) }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                            @else
                                <div style="height: 200px; background: #f0f4f8; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="font-size: 40px; color: #ccc;"></i>
                                </div>
                            @endif
                        </a>
                        <div class="card-body">
                            <a href="{{ route('shop.product.detail', $product->id) }}" class="text-decoration-none">
                                <p style="font-size: 13px; color: #333; margin-bottom: 5px; height: 40px; overflow: hidden;">{{ $product->title }}</p>
                            </a>
                            <div class="stars mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                                @endfor
                                <span style="font-size: 12px; color: #999;">({{ $product->reviews->count() }})</span>
                            </div>
                            @if($product->discount > 0)
                                <div class="old-price">{{ number_format($product->price, 2) }} ₺</div>
                                <div class="price">{{ number_format($product->discounted_price, 2) }} ₺</div>
                            @else
                                <div class="price">{{ number_format($product->price, 2) }} ₺</div>
                            @endif
                            <div class="kdv-price">KDV Dahil: {{ number_format($product->kdv_dahil_fiyat, 2) }} ₺</div>
                            <div style="font-size: 11px; color: #999;">{{ $product->garanti }} Yıl Garanti</div>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn-add-cart">
                                    <i class="bi bi-cart-plus"></i> Sepete Ekle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- TÜM ÜRÜNLER --}}
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: #1E3A5F; font-weight: bold;">
                <i class="bi bi-grid"></i> Tüm Ürünler
            </h4>
            <a href="{{ route('shop.products') }}" style="color: #3DA8E8; text-decoration: none; font-size: 14px;">
                Tümünü Gör <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-md-3 col-6">
                    <div class="product-card position-relative">
                        @if($product->discount > 0)
                            <div class="discount-badge">-%{{ $product->discount }}</div>
                        @endif
                        <a href="{{ route('shop.product.detail', $product->id) }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                            @else
                                <div style="height: 200px; background: #f0f4f8; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="font-size: 40px; color: #ccc;"></i>
                                </div>
                            @endif
                        </a>
                        <div class="card-body">
                            <a href="{{ route('shop.product.detail', $product->id) }}" class="text-decoration-none">
                                <p style="font-size: 13px; color: #333; margin-bottom: 5px; height: 40px; overflow: hidden;">{{ $product->title }}</p>
                            </a>
                            <div class="stars mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            @if($product->discount > 0)
                                <div class="old-price">{{ number_format($product->price, 2) }} ₺</div>
                                <div class="price">{{ number_format($product->discounted_price, 2) }} ₺</div>
                            @else
                                <div class="price">{{ number_format($product->price, 2) }} ₺</div>
                            @endif
                            <div class="kdv-price">KDV Dahil: {{ number_format($product->kdv_dahil_fiyat, 2) }} ₺</div>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn-add-cart">
                                    <i class="bi bi-cart-plus"></i> Sepete Ekle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
