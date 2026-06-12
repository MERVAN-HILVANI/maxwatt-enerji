@extends('layouts.admin.app')

@section('title') Ürün Detay @endsection

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
        <div class="card-header">
            <h5 class="mb-0">Ürün Detayı</h5>
        </div>
        <div class="card-body">

            @if($product->images->count() > 0)
                @php
                    $sorted = $product->images->sortByDesc(fn($img) => $product->image == $img->image)->values();
                @endphp
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach($sorted as $img)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $img->image) }}"
                                 width="120" height="120"
                                 style="object-fit:cover; cursor:pointer;"
                                 class="img-thumbnail gallery-img"
                                 data-src="{{ asset('storage/' . $img->image) }}">
                            @if($product->image == $img->image)
                                <div><span class="badge bg-success mt-1">Ana Fotoğraf</span></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif($product->image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         width="120" height="120"
                         style="object-fit:cover; cursor:pointer;"
                         class="img-thumbnail gallery-img"
                         data-src="{{ asset('storage/' . $product->image) }}">
                    <div><span class="badge bg-success mt-1">Ana Fotoğraf</span></div>
                </div>
            @endif

            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $product->id }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ $product->category ? $product->category->name : 'Yok' }}</td>
                </tr>
                <tr>
                    <th>Ürün Adı</th>
                    <td>{{ $product->title }}</td>
                </tr>
                <tr>
                    <th>Anahtar Kelimeler</th>
                    <td>{{ $product->keywords }}</td>
                </tr>
                <tr>
                    <th>Kısa Açıklama</th>
                    <td>{{ $product->description }}</td>
                </tr>
                <tr>
                    <th>Detaylı Açıklama</th>
                    <td>{!! $product->detail !!}</td>
                </tr>
                <tr>
                    <th>Fiyat</th>
                    <td>{{ number_format($product->price, 2) }} ₺</td>
                </tr>
                <tr>
                    <th>Stok</th>
                    <td>{{ $product->stock }}</td>
                </tr>
                <tr>
                    <th>Min. Stok</th>
                    <td>{{ $product->minstock }}</td>
                </tr>
                <tr>
                    <th>İndirim</th>
                    <td>%{{ $product->discount }}</td>
                </tr>
                <tr>
                    <th>KDV</th>
                    <td>%{{ $product->kdv }}</td>
                </tr>
                <tr>
                    <th>Garanti Süresi</th>
                    <td>{{ $product->garanti }} Yıl</td>
                </tr>
                <tr>
                    <th>Durum</th>
                    <td>
                        @if($product->status)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Pasif</span>
                        @endif
                    </td>
                </tr>
            </table>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning">Düzenle</a>
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Geri</a>
        </div>
    </div>

    <script>
        var images = [];
        var currentIndex = 0;
        var modalInstance = null;

        document.addEventListener('DOMContentLoaded', function () {
            var imgs = document.querySelectorAll('.gallery-img');
            imgs.forEach(function(img, index) {
                images.push(img.getAttribute('data-src'));
                img.addEventListener('click', function() {
                    currentIndex = index;
                    openModal(currentIndex);
                });
            });
        });

        function openModal(index) {
            document.getElementById('modalImage').src = images[index];
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
