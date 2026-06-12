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
        .sidebar .logo-area {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .sidebar .logo-area img {
            width: 160px;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: rgba(61,168,232,0.3);
            color: #3DA8E8;
            border-left: 3px solid #3DA8E8;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
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
        .btn-primary {
            background-color: #1E7BC4;
            border-color: #1E7BC4;
        }
        .btn-warning {
            background-color: #3DA8E8;
            border-color: #3DA8E8;
            color: white;
        }
        .btn-warning:hover {
            background-color: #1E7BC4;
            border-color: #1E7BC4;
            color: white;
        }
        .table-dark {
            background-color: #0A1628 !important;
        }
        .card-header {
            background-color: #1E3A5F;
            color: white;
        }
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
</div>

<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0" style="color: #1E7BC4;">@yield('title')</h5>
        <div>
            @if(Auth::check())
                <span class="me-3">👤 {{ Auth::user()->name }}</span>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Çıkış
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('footer')
</body>
</html>
