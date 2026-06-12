@extends('layouts.admin.app')

@section('title') Kategori Detay @endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Kategori Detayı</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <th>Üst Kategori</th>
                    <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                </tr>
                <tr>
                    <th>Ad</th>
                    <td>{{ $category->name }}</td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td>{{ $category->slug }}</td>
                </tr>
                <tr>
                    <th>Açıklama</th>
                    <td>{{ $category->description }}</td>
                </tr>
                <tr>
                    <th>Durum</th>
                    <td>
                        @if($category->status)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Pasif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Resim</th>
                    <td>
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" width="100">
                        @else
                            Resim yok
                        @endif
                    </td>
                </tr>
            </table>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Düzenle</a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Geri</a>
        </div>
    </div>
@endsection
