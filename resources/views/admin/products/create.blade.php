@extends('layouts.admin.app')

@section('title') Yeni Ürün @endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Yeni Ürün Ekle</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="primary_image_index" id="primary_image_index" value="0">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->full_path }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ürün Adı</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Anahtar Kelimeler</label>
                    <input type="text" name="keywords" class="form-control"
                           value="{{ old('keywords') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kısa Açıklama</label>
                    <input type="text" name="description" class="form-control"
                           value="{{ old('description') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Detaylı Açıklama</label>
                    <textarea name="detail" id="detail" rows="5" class="form-control">{{ old('detail') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fotoğraflar</label>
                    <input type="file" name="images[]" class="form-control" multiple id="imageInput">
                    <small class="text-muted">Ctrl tuşuna basılı tutarak birden fazla fotoğraf seçebilirsiniz.</small>
                </div>

                <div class="d-flex flex-wrap gap-3 mb-3" id="previewArea"></div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Fiyat (₺)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control"
                               value="{{ old('price') }}" required oninput="hesaplaKdv()">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">KDV (%)</label>
                        <select name="kdv" id="kdv" class="form-select" onchange="hesaplaKdv()">
                            @foreach(range(1, 20) as $kdv)
                                <option value="{{ $kdv }}" {{ $kdv == 18 ? 'selected' : '' }}>%{{ $kdv }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">KDV Dahil Fiyat (₺)</label>
                        <input type="number" step="0.01" name="kdv_dahil_fiyat" id="kdv_dahil_fiyat"
                               class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">İndirim (%)</label>
                        <input type="number" name="discount" class="form-control"
                               value="{{ old('discount', 0) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control"
                               value="{{ old('stock', 0) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Min. Stok</label>
                        <input type="number" name="minstock" class="form-control"
                               value="{{ old('minstock', 0) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Garanti Süresi (Yıl)</label>
                        <select name="garanti" class="form-select">
                            @foreach(range(1, 10) as $yil)
                                <option value="{{ $yil }}">{{ $yil }} Yıl</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning">Kaydet</button>
                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Geri</a>
            </form>
        </div>
    </div>

    <script>
        function hesaplaKdv() {
            var fiyat = parseFloat(document.getElementById('price').value) || 0;
            var kdv = parseFloat(document.getElementById('kdv').value) || 0;
            var kdvDahil = fiyat * (1 + kdv / 100);
            document.getElementById('kdv_dahil_fiyat').value = kdvDahil.toFixed(2);
        }

        document.getElementById('imageInput').addEventListener('change', function() {
            const preview = document.getElementById('previewArea');
            preview.innerHTML = '';
            document.getElementById('primary_image_index').value = 0;

            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'text-center border rounded p-2';
                    div.id = 'preview_' + index;
                    div.innerHTML = `
                        <img src="${e.target.result}" width="100" class="rounded mb-1"><br>
                        ${index === 0
                        ? '<span class="badge bg-success">Ana Fotoğraf ✓</span>'
                        : `<button type="button" class="btn btn-sm btn-outline-primary" onclick="setPrimaryNew(${index}, this)">Ana Yap</button>`
                    }
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });

        function setPrimaryNew(index, btn) {
            document.querySelectorAll('#previewArea .badge.bg-success').forEach((badge) => {
                const parent = badge.closest('.text-center');
                const idx = parent.id.replace('preview_', '');
                badge.outerHTML = `<button type="button" class="btn btn-sm btn-outline-primary" onclick="setPrimaryNew(${idx}, this)">Ana Yap</button>`;
            });

            btn.outerHTML = '<span class="badge bg-success">Ana Fotoğraf ✓</span>';
            document.getElementById('primary_image_index').value = index;
        }
    </script>
@endsection
