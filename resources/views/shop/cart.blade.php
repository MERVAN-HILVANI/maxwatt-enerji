@extends('layouts.shop')

@section('title') Sepetim @endsection

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    <li class="breadcrumb-item active">Sepetim</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <h4 style="color:#1E3A5F; font-weight:bold;"><i class="bi bi-cart3"></i> Sepetim</h4>

        @if($cartItems->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-cart-x" style="font-size:60px; color:#ccc;"></i>
                <h5 class="mt-3 text-muted">Sepetiniz boş</h5>
                <a href="{{ route('shop.products') }}" class="btn mt-3 text-white" style="background:#3DA8E8;">
                    Alışverişe Başla
                </a>
            </div>
        @else
            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            @foreach($cartItems as $item)
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             width="80" height="80" style="object-fit:cover; border-radius:8px;">
                                    @endif
                                    <div class="ms-3 flex-grow-1">
                                        <a href="{{ route('shop.product.detail', $item->product->id) }}"
                                           class="text-decoration-none" style="color:#1E3A5F; font-weight:bold; font-size:14px;">
                                            {{ $item->product->title }}
                                        </a>
                                        <p class="mb-1" style="font-size:13px; color:#3DA8E8;">
                                            {{ number_format($item->product->discounted_price, 2) }} ₺
                                            <span style="font-size:11px; color:#999;">KDV Dahil: {{ number_format($item->product->kdv_dahil_fiyat, 2) }} ₺</span>
                                        </p>
                                        <div class="d-flex align-items-center gap-2">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}"
                                                        class="btn btn-sm btn-outline-secondary" style="width:30px; height:30px; padding:0;">-</button>
                                                <span class="mx-2" style="font-weight:bold;">{{ $item->quantity }}</span>
                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                        class="btn btn-sm btn-outline-secondary" style="width:30px; height:30px; padding:0;">+</button>
                                            </form>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong style="color:#1E3A5F; font-size:16px;">
                                            {{ number_format($item->product->discounted_price * $item->quantity, 2) }} ₺
                                        </strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Sepeti temizlemek istediğinize emin misiniz?')">
                                    <i class="bi bi-trash"></i> Sepeti Temizle
                                </button>
                            </form>
                            <a href="{{ route('shop.products') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Alışverişe Devam
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header" style="background:#1E3A5F; color:white;">
                            <h6 class="mb-0">Sipariş Özeti</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size:14px;">Ara Toplam</span>
                                <span style="font-size:14px;">{{ number_format($total, 2) }} ₺</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size:14px;">KDV</span>
                                <span style="font-size:14px;">{{ number_format($totalKdv, 2) }} ₺</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size:14px;">Kargo</span>
                                <span style="font-size:14px; color:green;">{{ $total >= 500 ? 'Ücretsiz' : '29.90 ₺' }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Toplam</strong>
                                <strong style="font-size:18px; color:#1E3A5F;">
                                    {{ number_format($total + $totalKdv + ($total >= 500 ? 0 : 29.90), 2) }} ₺
                                </strong>
                            </div>
                            <a href="{{ route('order.create') }}" class="btn w-100 text-white" style="background:#3DA8E8; padding:12px;">
                                <i class="bi bi-credit-card"></i> Siparişi Tamamla
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
