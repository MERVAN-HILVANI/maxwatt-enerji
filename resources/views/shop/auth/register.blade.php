@extends('layouts.shop')

@section('title') Kayıt Ol @endsection

@section('content')
    <div class="container" style="max-width: 500px; margin: 50px auto;">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0A1628, #1E3A5F);">
                <img src="{{ asset('images/logo-şefaf.png') }}" height="50">
                <h5 class="text-white mt-2 mb-0">Kayıt Ol</h5>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               required value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               required value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="05XX XXX XX XX" maxlength="11"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">10 veya 11 haneli (örn: 05001234567)</small>
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
                        <small class="text-muted">En az 8 karakter, 1 büyük harf, 1 küçük harf ve 1 rakam</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                                <i class="bi bi-eye" id="eyeIcon2"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 text-white" style="background: #1E3A5F;">
                        <i class="bi bi-person-plus"></i> Kayıt Ol
                    </button>
                </form>
                <hr>
                <p class="text-center mb-0" style="font-size: 13px;">
                    Zaten hesabınız var mı?
                    <a href="{{ route('customer.login') }}" style="color: #3DA8E8;">Giriş Yap</a>
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
