<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Refund;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminHomeController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 1)->count();
        $totalCategories = Category::count();

        $adminRole = Role::where('name', 'admin')->first();
        $totalAdmins = $adminRole ? $adminRole->users()->count() : 0;
        $totalCustomers = User::count() - $totalAdmins;

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $refundedOrders = Order::where('status', 'refunded')->count();

        // Ciro: Teslim edilenler - İade edilenler (minimum 0)
        $totalRevenue = max(0, Order::where('status', 'delivered')->sum('total_price')
            - Order::where('status', 'refunded')->sum('total_price'));

        $pendingRefunds = Refund::where('status', 'pending')->count();
        $pendingApprovals = Order::where('requires_approval', true)->where('admin_approved', false)->count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.home', compact(
            'totalProducts', 'activeProducts', 'totalCategories',
            'totalAdmins', 'totalCustomers', 'totalOrders',
            'pendingOrders', 'processingOrders', 'shippedOrders',
            'deliveredOrders', 'cancelledOrders', 'refundedOrders',
            'totalRevenue', 'pendingRefunds', 'pendingApprovals', 'recentOrders'
        ));
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|regex:/^[0-9]{10,11}$/',
        ], [
            'name.required' => 'Ad Soyad zorunludur.',
            'phone.regex' => 'Telefon numarası 10 veya 11 haneli olmalıdır.',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profiliniz güncellendi.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'current_password.required' => 'Mevcut şifre zorunludur.',
            'password.required' => 'Yeni şifre zorunludur.',
            'password.min' => 'Yeni şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
            'password.regex' => 'Şifre en az 1 büyük harf, 1 küçük harf ve 1 rakam içermelidir.',
        ]);

        $user = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mevcut şifreniz hatalı.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Şifreniz başarıyla değiştirildi.');
    }
}
