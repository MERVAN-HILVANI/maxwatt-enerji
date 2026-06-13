<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'items')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items', 'refund');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'shipped') {
            $data['cargo_company'] = $request->cargo_company;
            $data['cargo_tracking_number'] = $request->cargo_tracking_number;
            $data['shipped_at'] = now();
        }

        if ($request->status === 'delivered') {
            $data['delivered_at'] = now();
        }

        if ($request->admin_note) {
            $data['admin_note'] = $request->admin_note;
        }

        $order->update($data);

        return back()->with('success', 'Sipariş durumu güncellendi.');
    }

    public function confirmPayment(Order $order)
    {
        if ($order->payment_method === 'cash_on_delivery') {
            $order->update([
                'admin_approved' => true,
                'approved_at' => now(),
                'status' => 'processing',
            ]);
            return back()->with('success', 'Sipariş onaylandı ve hazırlanmaya başlandı.');
        }

        $order->update(['payment_status' => 'confirmed']);
        return back()->with('success', 'Ödeme onaylandı.');
    }

    public function rejectPayment(Order $order)
    {
        if ($order->payment_method === 'cash_on_delivery') {
            $order->update([
                'status' => 'cancelled',
                'admin_note' => 'Kapıda ödeme talebi reddedildi.',
            ]);
            return back()->with('success', 'Sipariş reddedildi.');
        }

        $order->update(['payment_status' => 'rejected']);
        return back()->with('success', 'Ödeme reddedildi.');
    }

    public function refunds()
    {
        $refunds = Refund::with('user', 'order')
            ->latest()
            ->paginate(20);

        return view('admin.orders.refunds', compact('refunds'));
    }

    public function updateRefund(Request $request, Refund $refund)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $data = [
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ];

        if ($request->status === 'approved') {
            $data['approved_at'] = now();
            // İade onaylanınca sipariş durumunu "İade Edildi" yap
            $refund->order->update(['status' => 'refunded']);
        }

        $refund->update($data);

        return back()->with('success', 'İade talebi güncellendi.');
    }
}
