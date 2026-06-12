@extends('layouts.admin.app')

@section('title') Ürün Düzenle @endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Ürün Düzenle</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="primary_image_id" id="primary_image_id" value="{{ $product->image }}">

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $item)
                            <option value="{{ $item->id }}"
                                {{ $product->category_id == $item->id ? 'selected' : '' }}>
                                {{ $item->full_path }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ürün Adı</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title', $product->title) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Anahtar Kelimeler</label>
                    <input type="text" name="keywords" class="form-control"
                           value="{{ old('keywords', $product->keywords) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kısa Açıklama</label>
                    <input type="text" name="description" class="form-control"
                           value="{{ old('description', $product->description) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Detaylı Açıklama</label>
                    <textarea name="detail" id="detail" rows="5" class="form-control">{{ old('detail', $product->detail) }}</textarea>
                </div>

                {{-- Fotoğraflar --}}
                <div class="mb-3">
                    <label class="form-label">Fotoğraflar</label>
                    <div class="d-flex flex-wrap gap-3 mb-2" id="imageContainer">
                        @foreach($product->images->sortByDesc(fn($img) => $product->image == $img->image)->values() as $img)
                            <div class="text-center border rounded p-2" id="img_{{ $img->id }}">
                                <img src="{{ asset('storage/' . $img->image) }}" width="100" height="100"
                                     style="object-fit:cover;" class="rounded mb-1">
                                <div class="d-flex flex-column gap-1 mt-1">
                                    @if($product->image == $img->image)
                                        <span class="badge bg-success" id="badge_{{ $img->id }}">Ana Fotoğraf ✓</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                id="badge_{{ $img->id }}"
                                                onclick="setPrimary('{{ $img->image }}', {{ $img->id }})">
                                            Ana Yap
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="deleteImage({{ $img->id }}, '{{ route('admin.product.image.destroy', $img->id) }}')">
                                        🗑 Sil
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Yeni Fotoğraf Ekle</label>
                        <input type="file" id="instantUpload" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Seçtiğiniz fotoğraflar hemen yüklenecektir.</small>
                        <div id="uploadProgress" class="mt-2" style="display:none;">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                            <span class="ms-2">Yükleniyor...</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Fiyat (₺)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control"
                               value="{{ old('price', $product->price) }}" required oninput="hesaplaKdv()">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">KDV (%)</label>
                        <select name="kdv" id="kdv" class="form-select" onchange="hesaplaKdv()">
                            @foreach(range(1, 20) as $kdv)
                                <option value="{{ $kdv }}" {{ $product->kdv == $kdv ? 'selected' : '' }}>%{{ $kdv }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">KDV Dahil Fiyat (₺)</label>
                        <input type="number" step="0.01" id="kdv_dahil_fiyat"
                               class="form-control bg-light" readonly
                               value="{{ number_format($product->price * (1 + $product->kdv / 100), 2, '.', '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">İndirim (%)</label>
                        <input type="number" name="discount" class="form-control"
                               value="{{ old('discount', $product->discount) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control"
                               value="{{ old('stock', $product->stock) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Min. Stok</label>
                        <input type="number" name="minstock" class="form-control"
                               value="{{ old('minstock', $product->minstock) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Garanti Süresi (Yıl)</label>
                        <select name="garanti" class="form-select">
                            @foreach(range(1, 10) as $yil)
                                <option value="{{ $yil }}" {{ $product->garanti == $yil ? 'selected' : '' }}>{{ $yil }} Yıl</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Durum</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $product->status ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$product->status ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning">Güncelle</button>
                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Geri</a>
            </form>
        </div>
    </div>

    <script>
        var uploadUrl = '{{ route('admin.product.image.upload', $product->id) }}';
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function hesaplaKdv() {
            var fiyat = parseFloat(document.getElementById('price').value) || 0;
            var kdv = parseFloat(document.getElementById('kdv').value) || 0;
            var kdvDahil = fiyat * (1 + kdv / 100);
            document.getElementById('kdv_dahil_fiyat').value = kdvDahil.toFixed(2);
        }

        document.getElementById('instantUpload').addEventListener('change', function() {
            var files = this.files;
            if (!files.length) return;

            document.getElementById('uploadProgress').style.display = 'block';

            var uploaded = 0;
            Array.from(files).forEach(function(file) {
                var formData = new FormData();
                formData.append('image', file);
                formData.append('_token', csrfToken);

                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        addImageToContainer(data.id, data.url, data.path);
                        uploaded++;
                        if (uploaded === files.length) {
                            document.getElementById('uploadProgress').style.display = 'none';
                            document.getElementById('instantUpload').value = '';
                        }
                    })
                    .catch(() => {
                        alert('Yükleme hatası!');
                        document.getElementById('uploadProgress').style.display = 'none';
                    });
            });
        });

        function addImageToContainer(id, url, path) {
            var container = document.getElementById('imageContainer');
            var div = document.createElement('div');
            div.className = 'text-center border rounded p-2';
            div.id = 'img_' + id;
            div.innerHTML = `
                <img src="${url}" width="100" height="100" style="object-fit:cover;" class="rounded mb-1">
                <div class="d-flex flex-column gap-1 mt-1">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="badge_${id}"
                        onclick="setPrimary('${path}', ${id})">
                        Ana Yap
                    </button>
                    <button type="button" class="btn btn-sm btn-danger"
                        onclick="deleteImage(${id}, '/admin/product/image/${id}')">
                        🗑 Sil
                    </button>
                </div>
            `;
            container.appendChild(div);
        }

        function setPrimary(imagePath, id) {
            document.querySelectorAll('[id^="badge_"]').forEach(el => {
                if (el.tagName === 'SPAN') {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm btn-outline-primary';
                    btn.id = el.id;
                    btn.textContent = 'Ana Yap';
                    var imgId = el.id.replace('badge_', '');
                    btn.setAttribute('onclick', `setPrimary('${imagePath}', ${imgId})`);
                    el.replaceWith(btn);
                }
            });

            var selected = document.getElementById('badge_' + id);
            if (selected) {
                var badge = document.createElement('span');
                badge.className = 'badge bg-success';
                badge.id = 'badge_' + id;
                badge.textContent = 'Ana Fotoğraf ✓';
                selected.replaceWith(badge);
            }

            document.getElementById('primary_image_id').value = imagePath;
        }

        function deleteImage(id, url) {
            if (!confirm('Bu fotoğrafı silmek istediğinize emin misiniz?')) return;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'DELETE'
                }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    document.getElementById('img_' + id).remove();
                }
            }).catch(() => {
                alert('Silme hatası!');
            });
        }
    </script>
@endsection
