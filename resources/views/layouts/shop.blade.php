<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Maxwatt Enerji - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --maxwatt-blue: #1E3A5F;
            --maxwatt-light-blue: #3DA8E8;
            --maxwatt-dark: #0A1628;
        }
        body { background-color: #f5f5f5; font-family: 'Segoe UI', sans-serif; }
        .navbar-top {
            background: var(--maxwatt-dark);
            padding: 8px 0;
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }
        .navbar-main {
            background: var(--maxwatt-blue);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .navbar-main .logo img { height: 45px; }
        .search-bar { flex: 1; max-width: 500px; margin: 0 20px; }
        .search-bar input {
            border-radius: 25px 0 0 25px;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
        }
        .search-bar button {
            border-radius: 0 25px 25px 0;
            background: var(--maxwatt-light-blue);
            border: none;
            padding: 10px 20px;
            color: white;
        }
        .navbar-icons a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 13px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .navbar-icons a i { font-size: 20px; margin-bottom: 2px; }
        .navbar-icons a:hover { color: var(--maxwatt-light-blue); }
        .cart-badge { position: relative; }
        .cart-badge span.badge-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .category-bar {
            background: var(--maxwatt-blue);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 0;
        }
        .category-bar a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 10px 16px;
            display: inline-block;
            font-size: 13px;
            transition: 0.2s;
        }
        .category-bar a:hover { background: var(--maxwatt-light-blue); color: white; }
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: 0.3s;
            border: 1px solid #eee;
            height: 100%;
        }
        .product-card:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.15); transform: translateY(-3px); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; }
        .product-card .card-body { padding: 12px; }
        .product-card .price { color: var(--maxwatt-blue); font-size: 18px; font-weight: bold; }
        .product-card .old-price { text-decoration: line-through; color: #999; font-size: 13px; }
        .product-card .kdv-price { color: var(--maxwatt-light-blue); font-size: 13px; }
        .btn-add-cart {
            background: var(--maxwatt-light-blue);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            width: 100%;
            font-size: 13px;
            transition: 0.2s;
        }
        .btn-add-cart:hover { background: var(--maxwatt-blue); color: white; }
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .footer {
            background: var(--maxwatt-dark);
            color: rgba(255,255,255,0.8);
            padding: 40px 0 20px;
            margin-top: 50px;
        }
        .footer h6 { color: var(--maxwatt-light-blue); margin-bottom: 15px; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; display: block; margin-bottom: 8px; font-size: 13px; }
        .footer a:hover { color: var(--maxwatt-light-blue); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 30px; padding-top: 20px; font-size: 13px; }
        .breadcrumb-area { background: white; padding: 10px 0; border-bottom: 1px solid #eee; font-size: 13px; }
        .stars { color: #ffc107; font-size: 14px; }
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: red;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        /* KULLANICI DROPDOWN */
        .user-dropdown { position: relative; display: inline-block; }
        .user-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            min-width: 220px;
            z-index: 1000;
            margin-top: 8px;
        }
        .user-dropdown-menu.show { display: block; }
        .user-dropdown-menu a,
        .user-dropdown-menu button {
            display: block;
            width: 100%;
            padding: 10px 15px;
            text-align: left;
            border: none;
            background: none;
            color: #333;
            text-decoration: none;
            font-size: 13px;
            transition: 0.2s;
        }
        .user-dropdown-menu a:hover,
        .user-dropdown-menu button:hover { background: #f0f4f8; color: #1E3A5F; }
        .user-dropdown-menu .dropdown-divider { border-top: 1px solid #eee; margin: 5px 0; }
    </style>
</head>
<body>

{{-- TOP BAR --}}
<div class="navbar-top">
    <div class="container d-flex justify-content-between">
        <span><i class="bi bi-telephone"></i> +90 212 000 00 00 &nbsp;|&nbsp; <i class="bi bi-envelope"></i> info@maxwattenerji.com</span>
        <span>
            @auth
                Hoş geldin, {{ Auth::user()->name }}!
            @else
                <a href="{{ route('customer.login') }}" class="text-white">Giriş Yap</a> &nbsp;|&nbsp;
                <a href="{{ route('customer.register') }}" class="text-white">Kayıt Ol</a>
            @endauth
        </span>
    </div>
</div>

{{-- MAIN NAVBAR --}}
<div class="navbar-main">
    <div class="container d-flex align-items-center">
        <a href="{{ route('home') }}" class="logo me-3">
            <img src="{{ asset('images/logo-şefaf.png') }}" alt="Maxwatt Enerji">
        </a>

        <form action="{{ route('shop.search') }}" method="GET" class="search-bar d-flex">
            <input type="text" name="q" placeholder="Ürün ara..." value="{{ request('q') }}">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>

        <div class="navbar-icons d-flex ms-auto align-items-center">
            @auth
                <a href="{{ route('favorite.index') }}">
                    <i class="bi bi-heart"></i>
                    <span>Favoriler</span>
                </a>
                <a href="{{ route('order.index') }}">
                    <i class="bi bi-box"></i>
                    <span>Siparişler</span>
                </a>

                {{-- KULLANICI DROPDOWN --}}
                <div class="user-dropdown ms-3">
                    <button id="userBtn" style="background:none; border:1px solid rgba(255,255,255,0.3); border-radius:20px; padding:6px 12px; cursor:pointer; display:flex; align-items:center; gap:6px; color:white; font-size:13px;">
                        <div style="width:28px; height:28px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-person" style="font-size:14px;"></i>
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down" style="font-size:11px;"></i>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <div class="px-3 py-2" style="border-bottom:1px solid #eee;">
                            <p class="mb-0 fw-bold" style="font-size:13px;">{{ Auth::user()->name }}</p>
                            <p class="mb-0 text-muted" style="font-size:12px;">{{ Auth::user()->email }}</p>
                            @if(!Auth::user()->hasVerifiedEmail())
                                <span class="badge bg-warning text-dark" style="font-size:10px;">E-posta Doğrulanmamış</span>
                            @else
                                <span class="badge bg-success" style="font-size:10px;">Doğrulanmış ✓</span>
                            @endif
                        </div>
                        <a href="{{ route('customer.profile') }}">
                            <i class="bi bi-person me-2"></i> Profilim
                        </a>
                        <a href="{{ route('order.index') }}">
                            <i class="bi bi-box me-2"></i> Siparişlerim
                        </a>
                        <a href="{{ route('favorite.index') }}">
                            <i class="bi bi-heart me-2"></i> Favorilerim
                        </a>
                        <a href="{{ route('cart.index') }}">
                            <i class="bi bi-cart me-2"></i> Sepetim
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('customer.logout') }}" method="POST">
                            @csrf
                            <button type="submit" style="color:#dc3545;">
                                <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <a href="{{ route('cart.index') }}" class="cart-badge ms-3">
                <i class="bi bi-cart3" style="font-size:20px;"></i>
                @auth
                    @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="badge-count">{{ $cartCount }}</span>
                    @endif
                @endauth
                <span style="font-size:13px;">Sepet</span>
            </a>
        </div>
    </div>
</div>

{{-- CATEGORY BAR --}}
<div class="category-bar">
    <div class="container">
        <a href="{{ route('shop.products') }}">Tüm Ürünler</a>
        @foreach(\App\Models\Category::where('status', 1)->take(8)->get() as $cat)
            <a href="{{ route('shop.category', $cat->id) }}">{{ $cat->name }}</a>
        @endforeach
    </div>
</div>

{{-- ALERTS --}}
<div class="container mt-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- CONTENT --}}
@yield('content')

{{-- FOOTER --}}
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ asset('images/logo-şefaf.png') }}" height="50" class="mb-3">
                <p style="font-size:13px;">Türkiye'nin güvenilir solar enerji sistemleri tedarikçisi.</p>
            </div>
            <div class="col-md-3">
                <h6>Kurumsal</h6>
                <a href="#">Hakkımızda</a>
                <a href="#">İletişim</a>
                <a href="#">Kariyer</a>
                <a href="#">Blog</a>
            </div>
            <div class="col-md-3">
                <h6>Müşteri Hizmetleri</h6>
                <a href="#">Sipariş Takibi</a>
                <a href="#">İade & Değişim</a>
                <a href="#">Garanti Koşulları</a>
                <a href="#">SSS</a>
            </div>
            <div class="col-md-3">
                <h6>İletişim</h6>
                <p style="font-size:13px;"><i class="bi bi-telephone"></i> +90 212 000 00 00</p>
                <p style="font-size:13px;"><i class="bi bi-envelope"></i> info@maxwattenerji.com</p>
                <p style="font-size:13px;"><i class="bi bi-geo-alt"></i> İstanbul, Türkiye</p>
                <div class="d-flex gap-2 mt-2">
                    <a href="#" class="text-white"><i class="bi bi-facebook" style="font-size:20px;"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-instagram" style="font-size:20px;"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin" style="font-size:20px;"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">© 2026 Maxwatt Enerji. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Kullanıcı dropdown
    var userBtn = document.getElementById('userBtn');
    var userMenu = document.getElementById('userDropdownMenu');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function() {
            userMenu.classList.remove('show');
        });

        userMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
</script>

@yield('scripts')
</body>
</html>
