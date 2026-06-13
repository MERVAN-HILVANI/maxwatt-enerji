@extends('layouts.admin.app')

@section('title') İade Talepleri @endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">İade Talepleri</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Siparişlere Dön
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Tarih</th>
                    <th>Sebep</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                @forelse($refunds as $refund)
                    <tr>
                        <td><strong>{{ $refund->order->order_number }}</strong></td>
                        <td>{{ $refund->user->name }}</td>
                        <td>{{ $refund->created_at->format('d.m.Y H:i') }}</td>
                        <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $refund->reason }}
                        </td>
                        <td>
                        <span class="badge bg-{{ $refund->status_color }}">
                            {{ $refund->status_label }}
                        </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $refund->order->id) }}"
                               class="btn btn-sm btn-info">Detay</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Henüz iade talebi yok.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $refunds->links() }}
        </div>
    </div>
@endsection
