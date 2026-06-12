@extends('layouts.admin.app')

@section('title') Dashboard @endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white mb-3" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box me-2"></i>Toplam Ürün</h5>
                    <h2>{{ \App\Models\Product::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white mb-3" style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-grid me-2"></i>Toplam Kategori</h5>
                    <h2>{{ \App\Models\Category::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white mb-3" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people me-2"></i>Toplam Kullanıcı</h5>
                    <h2>{{ \App\Models\User::count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="background-color: #1a1a2e; color: #f39c12;">
            <h5 class="mb-0">⚡ Maxwatt Enerji - Solar Sistemleri Yönetim Paneli</h5>
        </div>
        <div class="card-body">
            <p>Hoş geldiniz! Sol menüden kategoriler ve ürünleri yönetebilirsiniz.</p>
            <a href="{{ route('admin.product.create') }}" class="btn btn-warning me-2">
                <i class="bi bi-plus-circle"></i> Yeni Ürün Ekle
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">
                <i class="bi bi-plus-circle"></i> Yeni Kategori Ekle
            </a>
        </div>
    </div>
@endsection
