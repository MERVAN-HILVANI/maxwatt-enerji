@extends('layouts.admin.app')

@section('title') Yeni Kategori @endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Yeni Kategori Ekle</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Üst Kategori (Opsiyonel)</label>
                    <select name="parent_id" class="form-select">
                        <option value="">Ana Kategori</option>
                        @foreach($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori Adı</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <input type="text" name="description" class="form-control"
                           value="{{ old('description') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Resim</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning">Kaydet</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Geri</a>
            </form>
        </div>
    </div>
@endsection
