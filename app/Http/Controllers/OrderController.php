<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items', 'refund')
            ->latest()
            ->get();

        return view('shop.orders', compact('orders'));
    }

    public function create()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.images')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->discounted_price * $item->quantity;
        });

        $totalKdv = $cartItems->sum(function($item) {
            return ($item->product->kdv_dahil_fiyat - $item->product->discounted_price) * $item->quantity;
        });

        return view('shop.checkout', compact('cartItems', 'total', 'totalKdv'));
    }

    public function store(Request $request)
    {
        // Zorunlu alan validasyonu
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email',
            'city' => 'required|string',
            'district' => 'required|string',
            'mahalle' => 'required|string',
            'sokak' => 'required|string',
            'apartman' => 'required|string',
            'daire' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer,credit_card',
        ], [
            'name.required' => 'Ad Soyad zorunludur.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'phone.regex' => 'Telefon numarası 10 veya 11 haneli olmalıdır (örn: 05001234567).',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'city.required' => 'İl seçimi zorunludur.',
            'district.required' => 'İlçe seçimi zorunludur.',
            'mahalle.required' => 'Mahalle seçimi zorunludur.',
            'sokak.required' => 'Cadde/Sokak zorunludur.',
            'apartman.required' => 'Apartman bilgisi zorunludur.',
            'daire.required' => 'Daire numarası zorunludur.',
            'address.required' => 'Adres zorunludur.',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->discounted_price * $item->quantity;
        });

        $totalKdv = $cartItems->sum(function($item) {
            return ($item->product->kdv_dahil_fiyat - $item->product->discounted_price) * $item->quantity;
        });

        $user = Auth::user();

        // E-posta doğrulama kontrolü
        if (!$user->isEmailVerified()) {
            return back()->with('error', 'Sipariş verebilmek için e-posta adresinizi doğrulamanız gerekmektedir.');
        }

        // Banka havalesi - dekont zorunlu
        if ($request->payment_method === 'bank_transfer') {
            if (!$request->hasFile('payment_receipt')) {
                return back()->with('error', 'Banka havalesi için ödeme dekontu yüklemeniz zorunludur.');
            }
        }

        // Kredi kartı - kart bilgileri zorunlu
        if ($request->payment_method === 'credit_card') {
            $request->validate([
                'card_number' => 'required|string',
                'card_name' => 'required|string',
                'card_expiry' => 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                'card_cvv' => 'required|string|size:3',
            ], [
                'card_number.required' => 'Kart numarası zorunludur.',
                'card_name.required' => 'Kart sahibi adı zorunludur.',
                'card_expiry.required' => 'Son kullanma tarihi zorunludur.',
                'card_expiry.regex' => 'Son kullanma tarihi MM/YY formatında olmalıdır.',
                'card_cvv.required' => 'CVV kodu zorunludur.',
                'card_cvv.size' => 'CVV kodu 3 haneli olmalıdır.',
            ]);
        }

        // Kapıda ödeme kontrolleri
        $requiresApproval = false;
        if ($request->payment_method === 'cash_on_delivery') {
            $request->validate([
                'tc_kimlik' => 'required|string|size:11|regex:/^[0-9]{11}$/',
                'dogum_tarihi' => 'required|date|before:today',
            ], [
                'tc_kimlik.required' => 'Kapıda ödeme için TC Kimlik No zorunludur.',
                'tc_kimlik.size' => 'TC Kimlik No 11 haneli olmalıdır.',
                'tc_kimlik.regex' => 'TC Kimlik No sadece rakamlardan oluşmalıdır.',
                'dogum_tarihi.required' => 'Kapıda ödeme için doğum tarihi zorunludur.',
                'dogum_tarihi.before' => 'Geçerli bir doğum tarihi giriniz.',
            ]);

            // 5000₺ limit
            if ($total > 5000) {
                return back()->with('error', 'Kapıda ödemede maksimum sipariş tutarı 5.000₺ dir.');
            }

            // Yeni müşteri 1000₺ limit
            if ($user->isNewCustomer() && $total > 1000) {
                return back()->with('error', 'İlk siparişinizde kapıda ödeme ile maksimum 1.000₺ tutarında sipariş verebilirsiniz.');
            }

            $requiresApproval = true;
        }

        // Dekont yükleme
        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'MW-' . strtoupper(uniqid()),
            'total_price' => $total,
            'total_kdv' => $totalKdv,
            'status' => 'pending',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'zip_code' => $request->zip_code,
            'note' => $request->note,
            'payment_method' => $request->payment_method,
            'payment_receipt' => $receiptPath,
            'payment_status' => $receiptPath ? 'pending' : null,
            'requires_approval' => $requiresApproval,
            'admin_approved' => false,
            'tc_kimlik' => $request->tc_kimlik,
            'dogum_tarihi' => $request->dogum_tarihi,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_title' => $item->product->title,
                'price' => $item->product->discounted_price,
                'kdv_rate' => $item->product->kdv,
                'kdv_amount' => ($item->product->kdv_dahil_fiyat - $item->product->discounted_price) * $item->quantity,
                'total_price' => $item->product->discounted_price * $item->quantity,
                'quantity' => $item->quantity,
                'product_image' => $item->product->image,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('order.show', $order->id)
            ->with('success', 'Siparişiniz başarıyla oluşturuldu! Sipariş No: ' . $order->order_number);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items', 'refund');
        return view('shop.order-detail', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Bu sipariş iptal edilemez.');
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Siparişiniz iptal edildi.');
    }

    public function refund(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canBeRefunded()) {
            return back()->with('error', 'Bu sipariş için iade talebinde bulunulamaz.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'İade sebebi zorunludur.',
            'reason.max' => 'İade sebebi en fazla 500 karakter olabilir.',
        ]);

        Refund::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'İade talebiniz alındı.');
    }

    public function uploadReceipt(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'payment_receipt.required' => 'Dekont dosyası zorunludur.',
            'payment_receipt.mimes' => 'Dekont PDF, JPG veya PNG formatında olmalıdır.',
            'payment_receipt.max' => 'Dekont dosyası en fazla 5MB olabilir.',
        ]);

        $path = $request->file('payment_receipt')->store('receipts', 'public');
        $order->update([
            'payment_receipt' => $path,
            'payment_status' => 'pending',
        ]);

        return back()->with('success', 'Ödeme dekontu yüklendi.');
    }
}
