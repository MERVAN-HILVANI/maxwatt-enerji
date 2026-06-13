@extends('layouts.shop')

@section('title') Giriş Yap @endsection

@section('content')
    <div class="container" style="max-width: 450px; margin: 50px auto;">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0A1628, #1E3A5F);">
                <img src="{{ asset('images/logo-şefaf.png') }}" height="50">
                <h5 class="text-white mt-2 mb-0">Giriş Yap</h5>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('customer.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">E-posta <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               required value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'eyeIcon1')">
                                <i class="bi bi-eye" id="eyeIcon1"></i>
                            </button>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label for="remember" class="form-check-label" style="font-size:13px;">Beni Hatırla</label>
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 text-white" style="background: #1E3A5F;">
                        <i class="bi bi-box-arrow-in-right"></i> Giriş Yap
                    </button>
                </form>
                <hr>
                <p class="text-center mb-0" style="font-size: 13px;">
                    Hesabınız yok mu?
                    <a href="{{ route('customer.register') }}" style="color: #3DA8E8;">Kayıt Ol</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endsection
