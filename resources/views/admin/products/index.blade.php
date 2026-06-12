@extends('layouts.admin.app')

@section('title') Ürünler @endsection

@section('content')
    {{-- Lightbox Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark border-0">
                <div class="modal-body text-center p-2 position-relative">
                    <button type="button" onclick="closeModal()"
                            style="position:absolute; top:10px; right:10px; background:rgba(255,255,255,0.2); border:none; color:white; font-size:22px; width:36px; height:36px; border-radius:50%; cursor:pointer; z-index:10;">
                        ✕
                    </button>
                    <button type="button" onclick="prevImage()"
                            style="position:absolute; left:10px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.2); border:none; color:white; font-size:28px; width:44px; height:44px; border-radius:50%; cursor:pointer;">
                        ‹
                    </button>
                    <img id="modalImage" src="" class="img-fluid rounded" style="max-height:80vh;">
                    <button type="button" onclick="nextImage()"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.2); border:none; color:white; font-size:28px; width:44px; height:44px; border-radius:50%; cursor:pointer;">
                        ›
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ürün Listesi</h5>
            <a href="{{ route('admin.product.create') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-plus-circle"></i> Yeni Ürün
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kategori</th>
                    <th>Ürün Adı</th>
                    <th>Fiyat</th>
                    <th>KDV</th>
                    <th>KDV Dahil</th>
                    <th>Garanti</th>
                    <th>Stok</th>
                    <th>İndirim</th>
                    <th>Resim</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->category ? $product->category->name : 'Yok' }}</td>
                        <td>{{ $product->title }}</td>
                        <td>{{ number_format($product->price, 2) }} ₺</td>
                        <td>%{{ $product->kdv }}</td>
                        <td>{{ number_format($product->price * (1 + $product->kdv / 100), 2) }} ₺</td>
                        <td>{{ $product->garanti }} Yıl</td>
                        <td>{{ $product->stock }}</td>
                        <td>%{{ $product->discount }}</td>
                        <td>
                            @if($product->image)
                                @php
                                    $allImages = $product->images->pluck('image')->toArray();
                                    $allImagesJson = json_encode(array_map(fn($img) => asset('storage/' . $img), $allImages));
                                @endphp
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     width="60" height="60"
                                     style="object-fit:cover; cursor:pointer;"
                                     class="img-thumbnail"
                                     onclick="openModal({{ $allImagesJson }}, 0)">
                            @endif
                        </td>
                        <td>
                            @if($product->status)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-info btn-sm">Göster</a>
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning btn-sm">Düzenle</a>
                            <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Henüz ürün eklenmemiş.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        var images = [];
        var currentIndex = 0;
        var modalInstance = null;

        function openModal(imgArray, index) {
            images = imgArray;
            currentIndex = index;
            document.getElementById('modalImage').src = images[currentIndex];
            var modal = document.getElementById('imageModal');
            modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
            modalInstance.show();
        }

        function closeModal() {
            if (modalInstance) modalInstance.hide();
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            document.getElementById('modalImage').src = images[currentIndex];
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            document.getElementById('modalImage').src = images[currentIndex];
        }
    </script>
@endsection
