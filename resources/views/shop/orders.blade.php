@extends('layouts.shop')

@section('title') Siparişlerim @endsection

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    <li class="breadcrumb-item active">Siparişlerim</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <h4 style="color:#1E3A5F; font-weight:bold;"><i class="bi bi-box"></i> Siparişlerim</h4>

        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-box" style="font-size:60px; color:#ccc;"></i>
                <h5 class="mt-3 text-muted">Henüz siparişiniz yok</h5>
                <a href="{{ route('shop.products') }}" class="btn mt-3 text-white" style="background:#3DA8E8;">
                    Alışverişe Başla
                </a>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#1E3A5F; color:white;">
                        <tr>
                            <th>Sipariş No</th>
                            <th>Tarih</th>
                            <th>Ürünler</th>
                            <th>Toplam</th>
                            <th>Durum</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $order->items->count() }} ürün</td>
                                <td><strong>{{ number_format($order->total_price, 2) }} ₺</strong></td>
                                <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                                </td>
                                <td>
                                    <a href="{{ route('order.show', $order->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detay
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
