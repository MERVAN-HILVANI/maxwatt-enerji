@extends('layouts.admin.app')

@section('title') Kategori Düzenle @endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Kategori Düzenle</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Üst Kategori (Opsiyonel)</label>
                    <select name="parent_id" class="form-select">
                        <option value="">Ana Kategori</option>
                        @foreach($categories as $item)
                            <option value="{{ $item->id }}"
                                {{ $category->parent_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori Adı</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <input type="text" name="description" class="form-control"
                           value="{{ old('description', $category->description) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Resim</label>
                    <input type="file" name="image" class="form-control">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" width="80" class="mt-2">
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $category->status ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$category->status ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning">Güncelle</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Geri</a>
            </form>
        </div>
    </div>
@endsection
