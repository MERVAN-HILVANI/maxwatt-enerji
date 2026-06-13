@extends('layouts.admin.app')

@section('title') Sipariş Detayı @endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            {{-- SİPARİŞ ÜRÜNLERİ --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $order->order_number }}</h5>
                    <span class="badge bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>Ürün</th>
                            <th>Fiyat</th>
                            <th>Adet</th>
                            <th>Toplam</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}"
                                                 width="50" height="50" style="object-fit:cover; border-radius:5px;" class="me-2">
                                        @endif
                                        <span style="font-size:13px;">{{ $item->product_title }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($item->price, 2) }} ₺</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total_price, 2) }} ₺</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Ara Toplam:</strong></td>
                            <td>{{ number_format($order->total_price, 2) }} ₺</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>KDV:</strong></td>
                            <td>{{ number_format($order->total_kdv, 2) }} ₺</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Genel Toplam:</strong></td>
                            <td><strong>{{ number_format($order->total_price + $order->total_kdv, 2) }} ₺</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- KAPIDA ÖDEME ONAYI --}}
            @if($order->payment_method == 'cash_on_delivery')
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bi bi-person-badge"></i> Kapıda Ödeme - Kimlik Bilgileri</h6>
                        @if($order->admin_approved)
                            <span class="badge bg-success">Onaylandı ✓</span>
                        @else
                            <span class="badge bg-warning">Onay Bekliyor</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1" style="font-size:13px;"><strong>TC Kimlik No:</strong> {{ $order->tc_kimlik ?? 'Girilmemiş' }}</p>
                                <p class="mb-0" style="font-size:13px;"><strong>Doğum Tarihi:</strong> {{ $order->dogum_tarihi ? $order->dogum_tarihi->format('d.m.Y') : 'Girilmemiş' }}</p>
                            </div>
                        </div>
                        @if(!$order->admin_approved)
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.orders.confirm.payment', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Siparişi Onayla
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.reject.payment', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-x-circle"></i> Siparişi Reddet
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-success mb-0"><i class="bi bi-check-circle"></i> Sipariş {{ $order->approved_at ? $order->approved_at->format('d.m.Y H:i') : '' }} tarihinde onaylandı.</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- DURUM GÜNCELLE --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Sipariş Durumunu Güncelle</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Durum</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Hazırlanıyor</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Kargoda</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                    <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>İade Edildi</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kargo Firması</label>
                                <select name="cargo_company" class="form-select">
                                    <option value="">Seçin</option>
                                    <option value="Yurtiçi Kargo" {{ $order->cargo_company == 'Yurtiçi Kargo' ? 'selected' : '' }}>Yurtiçi Kargo</option>
                                    <option value="Aras Kargo" {{ $order->cargo_company == 'Aras Kargo' ? 'selected' : '' }}>Aras Kargo</option>
                                    <option value="MNG Kargo" {{ $order->cargo_company == 'MNG Kargo' ? 'selected' : '' }}>MNG Kargo</option>
                                    <option value="PTT Kargo" {{ $order->cargo_company == 'PTT Kargo' ? 'selected' : '' }}>PTT Kargo</option>
                                    <option value="Sürat Kargo" {{ $order->cargo_company == 'Sürat Kargo' ? 'selected' : '' }}>Sürat Kargo</option>
                                    <option value="DHL" {{ $order->cargo_company == 'DHL' ? 'selected' : '' }}>DHL</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kargo Takip No</label>
                                <input type="text" name="cargo_tracking_number" class="form-control"
                                       value="{{ $order->cargo_tracking_number }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Admin Notu</label>
                                <textarea name="admin_note" class="form-control" rows="2">{{ $order->admin_note }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Güncelle
                        </button>
                    </form>
                </div>
            </div>

            {{-- ÖDEME DEKONTU --}}
            @if($order->payment_method == 'bank_transfer')
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Ödeme Dekontu</h6>
                        <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_label }}</span>
                    </div>
                    <div class="card-body">
                        @if($order->payment_receipt)
                            <a href="{{ asset('storage/' . $order->payment_receipt) }}" target="_blank"
                               class="btn btn-outline-primary btn-sm mb-3">
                                <i class="bi bi-file-earmark-pdf"></i> Dekontu Görüntüle
                            </a>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.orders.confirm.payment', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Ödemeyi Onayla
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.reject.payment', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-x-circle"></i> Ödemeyi Reddet
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-muted">Henüz dekont yüklenmedi.</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- İADE TALEBİ --}}
            @if($order->refund)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">İade Talebi</h6>
                        <span class="badge bg-{{ $order->refund->status_color }}">{{ $order->refund->status_label }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>Sebep:</strong> {{ $order->refund->reason }}</p>
                        <p><strong>Talep Tarihi:</strong> {{ $order->refund->created_at->format('d.m.Y H:i') }}</p>
                        <form action="{{ route('admin.orders.refund.update', $order->refund->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Durum</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->refund->status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                    <option value="approved" {{ $order->refund->status == 'approved' ? 'selected' : '' }}>Onayla</option>
                                    <option value="rejected" {{ $order->refund->status == 'rejected' ? 'selected' : '' }}>Reddet</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Admin Notu</label>
                                <textarea name="admin_note" class="form-control" rows="2">{{ $order->refund->admin_note }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm">Güncelle</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            {{-- MÜŞTERİ BİLGİLERİ --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Müşteri Bilgileri</h6>
                </div>
                <div class="card-body" style="font-size:13px;">
                    <p class="mb-1"><strong>Ad:</strong> {{ $order->name }}</p>
                    <p class="mb-1"><strong>E-posta:</strong> {{ $order->email }}</p>
                    <p class="mb-1"><strong>Telefon:</strong> {{ $order->phone }}</p>
                    <p class="mb-1"><strong>Şehir:</strong> {{ $order->city }}</p>
                    <p class="mb-1"><strong>İlçe:</strong> {{ $order->district }}</p>
                    <p class="mb-0"><strong>Adres:</strong> {{ $order->address }}</p>
                </div>
            </div>

            {{-- SİPARİŞ BİLGİLERİ --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Sipariş Bilgileri</h6>
                </div>
                <div class="card-body" style="font-size:13px;">
                    <p class="mb-1"><strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p class="mb-1"><strong>Ödeme:</strong>
                        @if($order->payment_method == 'cash_on_delivery') Kapıda Ödeme
                        @elseif($order->payment_method == 'bank_transfer') Banka Havalesi
                        @else Kredi Kartı
                        @endif
                    </p>
                    @if($order->requires_approval)
                        <p class="mb-1"><strong>Admin Onayı:</strong>
                            @if($order->admin_approved)
                                <span class="badge bg-success">Onaylandı</span>
                            @else
                                <span class="badge bg-warning">Bekliyor</span>
                            @endif
                        </p>
                    @endif
                    @if($order->cargo_company)
                        <p class="mb-1"><strong>Kargo:</strong> {{ $order->cargo_company }}</p>
                    @endif
                    @if($order->cargo_tracking_number)
                        <p class="mb-1"><strong>Takip No:</strong> {{ $order->cargo_tracking_number }}</p>
                    @endif
                    @if($order->shipped_at)
                        <p class="mb-1"><strong>Kargoya Verildi:</strong> {{ $order->shipped_at->format('d.m.Y H:i') }}</p>
                    @endif
                    @if($order->delivered_at)
                        <p class="mb-1"><strong>Teslim Edildi:</strong> {{ $order->delivered_at->format('d.m.Y') }}</p>
                    @endif
                    @if($order->note)
                        <p class="mb-0"><strong>Not:</strong> {{ $order->note }}</p>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-left"></i> Siparişlere Dön
            </a>
        </div>
    </div>
@endsection
