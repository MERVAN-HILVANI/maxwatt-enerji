@extends('layouts.admin.app')

@section('title') Siparişler @endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sipariş Listesi</h5>
            <a href="{{ route('admin.orders.refunds') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-return-left"></i> İade Talepleri
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Tarih</th>
                    <th>Tutar</th>
                    <th>Ödeme</th>
                    <th>Ödeme Durumu</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 2) }} ₺</td>
                        <td>
                            @if($order->payment_method == 'cash_on_delivery')
                                <span class="badge bg-secondary">Kapıda Ödeme</span>
                            @elseif($order->payment_method == 'bank_transfer')
                                <span class="badge bg-info">Havale</span>
                            @else
                                <span class="badge bg-primary">Kredi Kartı</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status)
                                <span class="badge bg-{{ $order->payment_status_color }}">
                                {{ $order->payment_status_label }}
                            </span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                        <span class="badge bg-{{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="btn btn-sm btn-info">Detay</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Henüz sipariş yok.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
