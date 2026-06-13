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
        body { background-color: #f0f4f8; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0A1628, #1E3A5F);
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
        }
        .sidebar .logo-area { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar .logo-area img { width: 160px; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; display: block; padding: 12px 20px; transition: 0.3s; }
        .sidebar a:hover { background-color: rgba(61,168,232,0.3); color: #3DA8E8; border-left: 3px solid #3DA8E8; }
        .main-content { margin-left: 250px; padding: 20px; }
        .topbar {
            background: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-primary { background-color: #1E7BC4; border-color: #1E7BC4; }
        .btn-warning { background-color: #3DA8E8; border-color: #3DA8E8; color: white; }
        .btn-warning:hover { background-color: #1E7BC4; border-color: #1E7BC4; color: white; }
        .table-dark { background-color: #0A1628 !important; }
        .card-header { background-color: #1E3A5F; color: white; }
        .admin-dropdown { position: relative; display: inline-block; }
        .admin-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            min-width: 220px;
            z-index: 1000;
            margin-top: 5px;
        }
        .admin-dropdown-menu.show { display: block; }
        .admin-dropdown-menu a,
        .admin-dropdown-menu button {
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
        .admin-dropdown-menu a:hover,
        .admin-dropdown-menu button:hover { background: #f0f4f8; color: #1E3A5F; }
        .admin-dropdown-menu .dropdown-divider { border-top: 1px solid #eee; margin: 5px 0; }
        .admin-user-btn {
            background: none;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 6px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #333;
            transition: 0.2s;
        }
        .admin-user-btn:hover { background: #f0f4f8; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo-area">
        <img src="{{ asset('images/logo-şefaf.png') }}" alt="Maxwatt Enerji">
    </div>
    <a href="{{ route('admin.index') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="{{ route('admin.categories.index') }}"><i class="bi bi-grid me-2"></i> Kategoriler</a>
    <a href="{{ route('admin.product.index') }}"><i class="bi bi-box me-2"></i> Ürünler</a>
    <a href="{{ route('admin.orders.index') }}"><i class="bi bi-bag me-2"></i> Siparişler</a>
    <a href="{{ route('admin.orders.refunds') }}"><i class="bi bi-arrow-return-left me-2"></i> İade Talepleri</a>
</div>

<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0" style="color: #1E7BC4;">@yield('title')</h5>
        <div class="d-flex align-items-center gap-3">
            @if(Auth::guard('admin')->check())
                <div class="admin-dropdown">
                    <button class="admin-user-btn" id="adminUserBtn">
                        <div style="width:32px; height:32px; background:#1E3A5F; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-person" style="color:white; font-size:16px;"></i>
                        </div>
                        <span>{{ Auth::guard('admin')->user()->name }}</span>
                        <i class="bi bi-chevron-down" style="font-size:11px;"></i>
                    </button>
                    <div class="admin-dropdown-menu" id="adminDropdownMenu">
                        <div class="px-3 py-2" style="border-bottom:1px solid #eee;">
                            <p class="mb-0 fw-bold" style="font-size:13px;">{{ Auth::guard('admin')->user()->name }}</p>
                            <p class="mb-0 text-muted" style="font-size:12px;">{{ Auth::guard('admin')->user()->email }}</p>
                            <span class="badge" style="background:#1E3A5F; font-size:10px;">Admin</span>
                        </div>
                        <a href="{{ route('admin.index') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.profile') }}">
                            <i class="bi bi-person me-2"></i> Profilim
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2"></i> Şifre Değiştir
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" style="color:#dc3545;">
                                <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

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

    @yield('content')
</div>

{{-- ŞİFRE DEĞİŞTİR MODAL --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#1E3A5F; color:white;">
                <h6 class="modal-title"><i class="bi bi-key"></i> Şifre Değiştir</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.change.password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Mevcut Şifre</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yeni Şifre</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">En az 8 karakter, 1 büyük harf, 1 küçük harf ve 1 rakam içermelidir.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yeni Şifre Tekrar</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Şifreyi Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('adminUserBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('adminDropdownMenu').classList.toggle('show');
    });

    document.addEventListener('click', function() {
        document.getElementById('adminDropdownMenu').classList.remove('show');
    });

    document.getElementById('adminDropdownMenu').addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

@yield('footer')
</body>
</html>
