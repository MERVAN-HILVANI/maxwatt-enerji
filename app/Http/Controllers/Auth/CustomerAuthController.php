<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function loginForm()
    {
        return view('shop.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre zorunludur.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('home'));
        }

        return back()->with('error', 'E-posta veya şifre hatalı.');
    }

    public function registerForm()
    {
        return view('shop.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'phone' => 'nullable|string|regex:/^[0-9]{10,11}$/',
        ], [
            'name.required' => 'Ad Soyad zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',
            'password.required' => 'Şifre zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
            'password.regex' => 'Şifre en az 1 büyük harf, 1 küçük harf ve 1 rakam içermelidir.',
            'phone.regex' => 'Telefon numarası 10 veya 11 haneli olmalıdır (örn: 05001234567).',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Hoş geldiniz ' . $user->name . '! Lütfen e-posta adresinizi doğrulayın.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->take(5)->get();
        return view('shop.auth.profile', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|regex:/^[0-9]{10,11}$/',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'district' => 'nullable|string',
        ], [
            'name.required' => 'Ad Soyad zorunludur.',
            'phone.regex' => 'Telefon numarası 10 veya 11 haneli olmalıdır (örn: 05001234567).',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'password.min' => 'Şifre en az 8 karakter olmalıdır.',
                'password.confirmed' => 'Şifreler eşleşmiyor.',
                'password.regex' => 'Şifre en az 1 büyük harf, 1 küçük harf ve 1 rakam içermelidir.',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profiliniz güncellendi.');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('home')->with('error', 'Doğrulama linki geçersiz.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('success', 'E-posta adresiniz zaten doğrulanmış.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('home')->with('success', 'E-posta adresiniz başarıyla doğrulandı!');
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('success', 'E-posta adresiniz zaten doğrulanmış.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Doğrulama e-postası gönderildi.');
    }
}
