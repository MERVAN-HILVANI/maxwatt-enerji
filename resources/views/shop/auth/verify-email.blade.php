@extends('layouts.shop')

@section('title') E-posta Doğrulama @endsection

@section('content')
    <div class="container" style="max-width: 500px; margin: 50px auto;">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0A1628, #1E3A5F);">
                <img src="{{ asset('images/logo-şefaf.png') }}" height="50">
                <h5 class="text-white mt-2 mb-0">E-posta Doğrulama</h5>
            </div>
            <div class="card-body p-4 text-center">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <i class="bi bi-envelope-check" style="font-size:60px; color:#3DA8E8;"></i>
                <h5 class="mt-3">E-postanızı Doğrulayın</h5>
                <p class="text-muted" style="font-size:14px;">
                    Kayıt olduğunuz e-posta adresine bir doğrulama linki gönderdik.
                    Lütfen e-postanızı kontrol edin ve linke tıklayın.
                </p>
                <p class="text-muted" style="font-size:13px;">
                    E-posta gelmedi mi?
                </p>
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn text-white" style="background:#3DA8E8;">
                        <i class="bi bi-envelope"></i> Tekrar Gönder
                    </button>
                </form>
                <hr>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
