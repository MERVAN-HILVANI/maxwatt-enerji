@extends('layouts.admin.app')

@section('title') Dashboard @endsection

@section('content')

    {{-- İSTATİSTİK KARTLARI --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 text-white h-100" style="background:#E87722;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:100px;">
                    <p class="mb-0" style="font-size:12px;">Toplam Ürün</p>
                    <h3 class="mb-0">{{ $totalProducts }}</h3>
                    <small style="opacity:0.8;">Aktif: {{ $activeProducts }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-white h-100" style="background:#0A1628;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:100px;">
                    <p class="mb-0" style="font-size:12px;">Toplam Kategori</p>
                    <h3 class="mb-0">{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-white h-100" style="background:#198754;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:100px;">
                    <p class="mb-0" style="font-size:12px;">Toplam Müşteri</p>
                    <h3 class="mb-0">{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-white h-100" style="background:#6f42c1;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:100px;">
                    <p class="mb-0" style="font-size:12px;">Toplam Admin</p>
                    <h3 class="mb-0">{{ $totalAdmins }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-white h-100" style="background:#3DA8E8;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:100px;">
                    <p class="mb-0" style="font-size:12px;">Toplam Sipariş</p>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-white h-100" style="background:#dc3545;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:100px;">
                    <p class="mb-0" style="font-size:12px;">Toplam Ciro</p>
                    <h4 class="mb-0">{{ number_format($totalRevenue, 0, ',', '.') }} ₺</h4>
                    <small style="opacity:0.8;">İadeler düşülmüş</small>
                </div>
            </div>
        </div>
    </div>

    {{-- SİPARİŞ DURUMLARI --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #ffc107 !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#ffc107;">{{ $pendingOrders }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">Beklemede</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #0dcaf0 !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#0dcaf0;">{{ $processingOrders }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">Hazırlanıyor</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #0d6efd !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#0d6efd;">{{ $shippedOrders }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">Kargoda</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #198754 !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#198754;">{{ $deliveredOrders }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">Teslim Edildi</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #dc3545 !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#dc3545;">{{ $cancelledOrders }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">İptal Edildi</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #fd7e14 !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#fd7e14;">{{ $pendingRefunds }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">İade Talebi</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center h-100" style="background:white; border-left: 4px solid #6f42c1 !important;">
                <div class="card-body d-flex flex-column justify-content-center" style="min-height:80px;">
                    <h4 class="mb-0" style="color:#6f42c1;">{{ $refundedOrders }}</h4>
                    <p class="mb-0" style="font-size:12px; color:#999;">İade Edildi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- UYARI KARTLARI --}}
    @if($pendingApprovals > 0 || $pendingRefunds > 0)
        <div class="row g-3 mb-4">
            @if($pendingApprovals > 0)
                <div class="col-md-6">
                    <div class="card border-0 border-start border-warning border-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-warning fw-bold"><i class="bi bi-exclamation-triangle"></i> Onay Bekleyen Sipariş</p>
                                <h4 class="mb-0">{{ $pendingApprovals }} Sipariş</h4>
                                <small class="text-muted">Kapıda ödeme admin onayı bekliyor</small>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-warning btn-sm">İncele</a>
                        </div>
                    </div>
                </div>
            @endif

            @if($pendingRefunds > 0)
                <div class="col-md-6">
                    <div class="card border-0 border-start border-danger border-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-danger fw-bold"><i class="bi bi-arrow-return-left"></i> İade Talebi</p>
                                <h4 class="mb-0">{{ $pendingRefunds }} Talep</h4>
                                <small class="text-muted">İnceleme bekliyor</small>
                            </div>
                            <a href="{{ route('admin.orders.refunds') }}" class="btn btn-danger btn-sm">İncele</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- SON SİPARİŞLER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-bag"></i> Son Siparişler</h6>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-warning">Tümünü Gör</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Tutar</th>
                    <th>Ödeme</th>
                    <th>Durum</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ number_format($order->total_price, 2) }} ₺</td>
                        <td>
                            @if($order->payment_method == 'cash_on_delivery')
                                <span class="badge bg-secondary">Kapıda</span>
                            @elseif($order->payment_method == 'bank_transfer')
                                <span class="badge bg-info">Havale</span>
                            @else
                                <span class="badge bg-primary">Kredi Kartı</span>
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                        <td><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">Detay</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-muted">Henüz sipariş yok.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- HIZLI ERİŞİM --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-lightning"></i> Maxwatt Enerji - Solar Sistemleri Yönetim Paneli</h6>
        </div>
        <div class="card-body">
            <p class="text-muted">Hoş geldiniz! Sol menüden kategoriler, ürünler ve siparişleri yönetebilirsiniz.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.product.create') }}" class="btn btn-warning">
                    <i class="bi bi-plus-circle"></i> Yeni Ürün Ekle
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-plus-circle"></i> Yeni Kategori Ekle
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-bag"></i> Siparişleri Görüntüle
                </a>
                <a href="{{ route('admin.orders.refunds') }}" class="btn btn-outline-danger">
                    <i class="bi bi-arrow-return-left"></i> İade Talepleri
                </a>
            </div>
        </div>
    </div>

@endsection
