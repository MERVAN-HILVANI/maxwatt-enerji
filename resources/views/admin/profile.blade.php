@extends('layouts.admin.app')

@section('title') Profilim @endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <div style="width:100px; height:100px; background:#1E3A5F; border-radius:50%; display:flex; align-items:center; justify-content:center; margin: 0 auto 15px;">
                        <i class="bi bi-person" style="font-size:50px; color:white;"></i>
                    </div>
                    <h5 class="mb-1">{{ Auth::guard('admin')->user()->name }}</h5>
                    <p class="text-muted mb-2" style="font-size:13px;">{{ Auth::guard('admin')->user()->email }}</p>
                    <span class="badge" style="background:#1E3A5F;">Admin</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Hesap Bilgileri</h6>
                </div>
                <div class="card-body" style="font-size:13px;">
                    <p class="mb-2"><strong>Ad Soyad:</strong> {{ Auth::guard('admin')->user()->name }}</p>
                    <p class="mb-2"><strong>E-posta:</strong> {{ Auth::guard('admin')->user()->email }}</p>
                    <p class="mb-2"><strong>Telefon:</strong> {{ Auth::guard('admin')->user()->phone ?? 'Girilmemiş' }}</p>
                    <p class="mb-0"><strong>Kayıt Tarihi:</strong> {{ Auth::guard('admin')->user()->created_at->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- PROFİL GÜNCELLE --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Profil Bilgilerimi Güncelle</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
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

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ad Soyad</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ Auth::guard('admin')->user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ Auth::guard('admin')->user()->phone }}"
                                       placeholder="05XX XXX XX XX" maxlength="11"
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">E-posta</label>
                                <input type="email" class="form-control bg-light"
                                       value="{{ Auth::guard('admin')->user()->email }}" disabled>
                                <small class="text-muted">E-posta adresi değiştirilemez.</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Güncelle
                        </button>
                    </form>
                </div>
            </div>

            {{-- ŞİFRE DEĞİŞTİR --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-key"></i> Şifre Değiştir</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.change.password') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mevcut Şifre</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Yeni Şifre</label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">En az 8 karakter, 1 büyük harf, 1 küçük harf ve 1 rakam</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Yeni Şifre Tekrar</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key"></i> Şifreyi Değiştir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
