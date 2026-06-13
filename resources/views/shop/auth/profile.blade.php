@extends('layouts.shop')

@section('title') Hesabım @endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            {{-- SOL MENU --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div style="width:80px; height:80px; background:#1E3A5F; border-radius:50%; display:flex; align-items:center; justify-content:center; margin: 0 auto 10px;">
                            <i class="bi bi-person" style="font-size:40px; color:white;"></i>
                        </div>
                        <h6 class="mb-0">{{ $user->name }}</h6>
                        <p style="font-size:12px; color:#999;">{{ $user->email }}</p>
                        @if($user->hasVerifiedEmail())
                            <span class="badge bg-success" style="font-size:11px;"><i class="bi bi-check-circle"></i> E-posta Doğrulandı</span>
                        @else
                            <span class="badge bg-warning text-dark" style="font-size:11px;"><i class="bi bi-exclamation-circle"></i> Doğrulanmamış</span>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action active" style="background:#1E3A5F; border-color:#1E3A5F;">
                            <i class="bi bi-person me-2"></i> Profilim
                        </a>
                        <a href="{{ route('order.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-box me-2"></i> Siparişlerim
                        </a>
                        <a href="{{ route('favorite.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-heart me-2"></i> Favorilerim
                        </a>
                        <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-cart me-2"></i> Sepetim
                        </a>
                        <a href="#" class="list-group-item list-group-item-action text-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                        </a>
                        <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
            </div>

            {{-- SAĞ KISIM --}}
            <div class="col-md-9">

                {{-- E-POSTA DOĞRULAMA UYARISI --}}
                @if(!$user->hasVerifiedEmail())
                    <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>E-posta adresiniz doğrulanmamış!</strong>
                            Sipariş verebilmek için e-posta adresinizi doğrulamanız gerekmektedir.
                        </div>
                        <form action="{{ route('verification.send') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-envelope"></i> Doğrulama E-postası Gönder
                            </button>
                        </form>
                    </div>
                @endif

                {{-- PROFİL BİLGİLERİ --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-person"></i> Profil Bilgilerim</h6>
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

                        <form action="{{ route('customer.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ad Soyad</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefon</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}"
                                           placeholder="05XX XXX XX XX" maxlength="11"
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">E-posta</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                        @if($user->hasVerifiedEmail())
                                            <span class="input-group-text bg-success text-white">
                                            <i class="bi bi-check-circle"></i> Doğrulandı
                                        </span>
                                        @else
                                            <span class="input-group-text bg-warning text-dark">
                                            <i class="bi bi-exclamation-circle"></i> Doğrulanmamış
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Şehir</label>
                                    <input type="text" name="city" class="form-control" value="{{ $user->city }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">İlçe</label>
                                    <input type="text" name="district" class="form-control" value="{{ $user->district }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Adres</label>
                                    <textarea name="address" class="form-control" rows="3">{{ $user->address }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Yeni Şifre <small class="text-muted">(değiştirmek istemiyorsanız boş bırakın)</small></label>
                                    <input type="password" name="password" class="form-control">
                                    <small class="text-muted">En az 8 karakter, 1 büyük harf, 1 küçük harf ve 1 rakam</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Şifre Tekrar</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn text-white" style="background:#1E3A5F;">
                                <i class="bi bi-save"></i> Güncelle
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SON SİPARİŞLER --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-box"></i> Son Siparişlerim</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($orders->isEmpty())
                            <p class="text-center py-4 text-muted">Henüz siparişiniz yok.</p>
                        @else
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Sipariş No</th>
                                    <th>Tarih</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                        <td>{{ number_format($order->total_price, 2) }} ₺</td>
                                        <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                        <td><a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Detay</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
