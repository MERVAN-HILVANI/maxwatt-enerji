@extends('layouts.shop')

@section('title') Sipariş Detayı @endsection

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('order.index') }}" style="color:#3DA8E8;">Siparişlerim</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color:#1E3A5F; font-weight:bold;"><i class="bi bi-box"></i> Sipariş Detayı</h4>
            <span class="badge bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- KARGO TAKİP --}}
        @if($order->status == 'shipped' && $order->cargo_tracking_number)
            <div class="alert alert-info mb-4">
                <i class="bi bi-truck"></i>
                <strong>Kargonuz Yolda!</strong>
                Kargo Firması: <strong>{{ $order->cargo_company }}</strong> |
                Takip No: <strong>{{ $order->cargo_tracking_number }}</strong>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                {{-- ÜRÜNLER --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-bag"></i> Sipariş Ürünleri</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                @if($item->product_image)
                                    <img src="{{ asset('storage/' . $item->product_image) }}"
                                         width="70" height="70" style="object-fit:cover; border-radius:8px;">
                                @endif
                                <div class="ms-3 flex-grow-1">
                                    <p class="mb-1" style="font-weight:bold; font-size:14px;">{{ $item->product_title }}</p>
                                    <p class="mb-0" style="font-size:13px; color:#999;">
                                        {{ $item->quantity }} adet × {{ number_format($item->price, 2) }} ₺
                                    </p>
                                    <p class="mb-0" style="font-size:12px; color:#3DA8E8;">
                                        KDV: %{{ $item->kdv_rate }} ({{ number_format($item->kdv_amount, 2) }} ₺)
                                    </p>
                                </div>
                                <strong style="color:#1E3A5F;">{{ number_format($item->total_price, 2) }} ₺</strong>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ara Toplam</span>
                            <span>{{ number_format($order->total_price, 2) }} ₺</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>KDV</span>
                            <span>{{ number_format($order->total_kdv, 2) }} ₺</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Genel Toplam</strong>
                            <strong style="color:#1E3A5F; font-size:16px;">
                                {{ number_format($order->total_price + $order->total_kdv, 2) }} ₺
                            </strong>
                        </div>
                    </div>
                </div>

                {{-- ÖDEME DEKONTU --}}
                @if($order->payment_method == 'bank_transfer')
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header" style="background:#1E3A5F; color:white;">
                            <h6 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Ödeme Dekontu</h6>
                        </div>
                        <div class="card-body">
                            @if($order->payment_receipt)
                                <p>
                                    <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_label }}</span>
                                    <a href="{{ asset('storage/' . $order->payment_receipt) }}" target="_blank"
                                       class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="bi bi-eye"></i> Dekontu Görüntüle
                                    </a>
                                </p>
                            @else
                                <p class="text-muted mb-3">Henüz dekont yüklemediniz.</p>
                                <form action="{{ route('order.receipt', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Ödeme Dekontunu Yükle</label>
                                        <input type="file" name="payment_receipt" class="form-control"
                                               accept=".pdf,.jpg,.jpeg,.png" required>
                                        <small class="text-muted">PDF, JPG veya PNG. Max: 5MB</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-upload"></i> Yükle
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- İPTAL --}}
                @if($order->canBeCancelled())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header" style="background:#dc3545; color:white;">
                            <h6 class="mb-0"><i class="bi bi-x-circle"></i> Siparişi İptal Et</h6>
                        </div>
                        <div class="card-body">
                            <p style="font-size:13px;" class="text-muted">Siparişiniz henüz hazırlanmaya başlanmadığı için iptal edebilirsiniz.</p>
                            <form action="{{ route('order.cancel', $order->id) }}" method="POST"
                                  onsubmit="return confirm('Siparişinizi iptal etmek istediğinize emin misiniz?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-x-circle"></i> Siparişi İptal Et
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- İADE --}}
                @if($order->canBeRefunded())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header" style="background:#fd7e14; color:white;">
                            <h6 class="mb-0"><i class="bi bi-arrow-return-left"></i> İade Talebi Oluştur</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('order.refund', $order->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">İade Sebebi</label>
                                    <textarea name="reason" class="form-control" rows="3"
                                              placeholder="İade sebebinizi açıklayın..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm text-white">
                                    <i class="bi bi-arrow-return-left"></i> İade Talebi Gönder
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- İADE DURUMU --}}
                @if($order->refund)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header" style="background:#1E3A5F; color:white;">
                            <h6 class="mb-0"><i class="bi bi-arrow-return-left"></i> İade Talebi Durumu</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Durum:</strong>
                                <span class="badge bg-{{ $order->refund->status_color }}">{{ $order->refund->status_label }}</span>
                            </p>
                            <p><strong>Sebep:</strong> {{ $order->refund->reason }}</p>
                            <p><strong>Talep Tarihi:</strong> {{ $order->refund->created_at->format('d.m.Y H:i') }}</p>
                            @if($order->refund->admin_note)
                                <p><strong>Admin Notu:</strong> {{ $order->refund->admin_note }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                {{-- SİPARİŞ BİLGİLERİ --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Sipariş Bilgileri</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2" style="font-size:13px;"><strong>Sipariş No:</strong> {{ $order->order_number }}</p>
                        <p class="mb-2" style="font-size:13px;"><strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                        <p class="mb-2" style="font-size:13px;"><strong>Ödeme:</strong>
                            @if($order->payment_method == 'cash_on_delivery') Kapıda Ödeme
                            @elseif($order->payment_method == 'bank_transfer') Banka Havalesi
                            @else Kredi Kartı
                            @endif
                        </p>
                        @if($order->cargo_company)
                            <p class="mb-2" style="font-size:13px;"><strong>Kargo:</strong> {{ $order->cargo_company }}</p>
                        @endif
                        @if($order->cargo_tracking_number)
                            <p class="mb-2" style="font-size:13px;"><strong>Takip No:</strong> {{ $order->cargo_tracking_number }}</p>
                        @endif
                        @if($order->shipped_at)
                            <p class="mb-2" style="font-size:13px;"><strong>Kargoya Verildi:</strong> {{ $order->shipped_at->format('d.m.Y') }}</p>
                        @endif
                        @if($order->delivered_at)
                            <p class="mb-2" style="font-size:13px;"><strong>Teslim Edildi:</strong> {{ $order->delivered_at->format('d.m.Y') }}</p>
                        @endif
                    </div>
                </div>

                {{-- TESLİMAT ADRESİ --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Teslimat Adresi</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1" style="font-size:13px;"><strong>{{ $order->name }}</strong></p>
                        <p class="mb-1" style="font-size:13px;">{{ $order->phone }}</p>
                        <p class="mb-1" style="font-size:13px;">{{ $order->address }}</p>
                        <p class="mb-1" style="font-size:13px;">{{ $order->district }} / {{ $order->city }}</p>
                        @if($order->note)
                            <hr>
                            <p class="mb-0" style="font-size:13px;"><strong>Not:</strong> {{ $order->note }}</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('order.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Siparişlerime Dön
                </a>
            </div>
        </div>
    </div>
@endsection
