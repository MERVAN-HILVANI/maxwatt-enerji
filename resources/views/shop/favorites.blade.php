@extends('layouts.shop')

@section('title') Favorilerim @endsection

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    <li class="breadcrumb-item active">Favorilerim</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <h4 style="color:#1E3A5F; font-weight:bold;"><i class="bi bi-heart"></i> Favorilerim</h4>

        @if($favorites->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-heart" style="font-size:60px; color:#ccc;"></i>
                <h5 class="mt-3 text-muted">Henüz favori ürününüz yok</h5>
                <a href="{{ route('shop.products') }}" class="btn mt-3 text-white" style="background:#3DA8E8;">
                    Alışverişe Başla
                </a>
            </div>
        @else
            <div class="row g-3">
                @foreach($favorites as $favorite)
                    <div class="col-md-3 col-6">
                        <div class="product-card position-relative">
                            @if($favorite->product->discount > 0)
                                <div class="discount-badge">-%{{ $favorite->product->discount }}</div>
                            @endif
                            <form action="{{ route('favorite.remove', $favorite->product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="favorite-btn active">
                                    <i class="bi bi-heart-fill" style="color:red;"></i>
                                </button>
                            </form>
                            <a href="{{ route('shop.product.detail', $favorite->product->id) }}">
                                @if($favorite->product->image)
                                    <img src="{{ asset('storage/' . $favorite->product->image) }}"
                                         alt="{{ $favorite->product->title }}">
                                @else
                                    <div style="height:200px; background:#f0f4f8; display:flex; align-items:center; justify-content:center;">
                                        <i class="bi bi-image" style="font-size:40px; color:#ccc;"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="card-body">
                                <a href="{{ route('shop.product.detail', $favorite->product->id) }}" class="text-decoration-none">
                                    <p style="font-size:13px; color:#333; margin-bottom:5px; height:40px; overflow:hidden;">
                                        {{ $favorite->product->title }}
                                    </p>
                                </a>
                                <div class="stars mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($favorite->product->average_rating) ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                @if($favorite->product->discount > 0)
                                    <div class="old-price">{{ number_format($favorite->product->price, 2) }} ₺</div>
                                    <div class="price">{{ number_format($favorite->product->discounted_price, 2) }} ₺</div>
                                @else
                                    <div class="price">{{ number_format($favorite->product->price, 2) }} ₺</div>
                                @endif
                                <div class="kdv-price">KDV Dahil: {{ number_format($favorite->product->kdv_dahil_fiyat, 2) }} ₺</div>
                                <form action="{{ route('cart.add', $favorite->product->id) }}" method="POST" class="mt-2">
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
        @endif
    </div>
@endsection
