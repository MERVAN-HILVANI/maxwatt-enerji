@extends('layouts.shop')

@section('title') {{ $product->title }} @endsection

@section('content')
    {{-- Lightbox Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark border-0">
                <div class="modal-body text-center p-2 position-relative">
                    <button type="button" onclick="closeModal()"
                            style="position:absolute; top:10px; right:10px; background:rgba(255,255,255,0.2); border:none; color:white; font-size:22px; width:36px; height:36px; border-radius:50%; cursor:pointer; z-index:10;">✕</button>
                    <button type="button" onclick="prevImage()"
                            style="position:absolute; left:10px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.2); border:none; color:white; font-size:28px; width:44px; height:44px; border-radius:50%; cursor:pointer;">‹</button>
                    <img id="modalImage" src="" class="img-fluid rounded" style="max-height:80vh;">
                    <button type="button" onclick="nextImage()"
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.2); border:none; color:white; font-size:28px; width:44px; height:44px; border-radius:50%; cursor:pointer;">›</button>
                </div>
            </div>
        </div>
    </div>

    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category_id) }}" style="color:#3DA8E8;">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active">{{ $product->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            {{-- FOTOĞRAFLAR --}}
            <div class="col-md-5">
                <div class="text-center mb-3">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" id="mainImage"
                             class="img-fluid rounded shadow-sm gallery-img"
                             style="max-height:400px; cursor:pointer; width:100%; object-fit:cover;"
                             data-src="{{ asset('storage/' . $product->image) }}">
                    @endif
                </div>
                @if($product->images->count() > 1)
                    <div class="d-flex gap-2 flex-wrap justify-content-center">
                        @foreach($product->images->sortByDesc(fn($img) => $product->image == $img->image)->values() as $img)
                            <img src="{{ asset('storage/' . $img->image) }}"
                                 width="70" height="70"
                                 style="object-fit:cover; cursor:pointer; border-radius:5px; border: 2px solid {{ $product->image == $img->image ? '#3DA8E8' : '#eee' }};"
                                 class="gallery-img"
                                 data-src="{{ asset('storage/' . $img->image) }}"
                                 onclick="document.getElementById('mainImage').src=this.src">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ÜRÜN BİLGİLERİ --}}
            <div class="col-md-7">
                <h4 style="color:#1E3A5F; font-weight:bold;">{{ $product->title }}</h4>

                {{-- YILDIZLAR --}}
                <div class="d-flex align-items-center mb-2">
                    <div class="stars me-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span style="font-size:13px; color:#999;">{{ $product->reviews->count() }} yorum</span>
                </div>

                {{-- FİYAT --}}
                <div class="mb-3">
                    @if($product->discount > 0)
                        <span style="text-decoration:line-through; color:#999; font-size:16px;">{{ number_format($product->price, 2) }} ₺</span>
                        <span style="font-size:28px; font-weight:bold; color:#1E3A5F; margin-left:10px;">{{ number_format($product->discounted_price, 2) }} ₺</span>
                        <span class="badge bg-danger ms-2">%{{ $product->discount }} İndirim</span>
                    @else
                        <span style="font-size:28px; font-weight:bold; color:#1E3A5F;">{{ number_format($product->price, 2) }} ₺</span>
                    @endif
                    <div style="font-size:14px; color:#3DA8E8; margin-top:5px;">
                        KDV Dahil: <strong>{{ number_format($product->kdv_dahil_fiyat, 2) }} ₺</strong>
                        <span style="font-size:12px; color:#999;">(KDV: %{{ $product->kdv }})</span>
                    </div>
                </div>

                {{-- ÖZELLİKLER --}}
                <div class="d-flex gap-3 mb-3">
                    <div class="text-center p-2 rounded" style="background:#f0f4f8; min-width:80px;">
                        <i class="bi bi-shield-check" style="color:#3DA8E8; font-size:20px;"></i>
                        <p class="mb-0" style="font-size:12px; font-weight:bold;">{{ $product->garanti }} Yıl</p>
                        <p class="mb-0" style="font-size:11px; color:#999;">Garanti</p>
                    </div>
                    <div class="text-center p-2 rounded" style="background:#f0f4f8; min-width:80px;">
                        <i class="bi bi-box-seam" style="color:#3DA8E8; font-size:20px;"></i>
                        <p class="mb-0" style="font-size:12px; font-weight:bold;">{{ $product->stock }}</p>
                        <p class="mb-0" style="font-size:11px; color:#999;">Stok</p>
                    </div>
                    <div class="text-center p-2 rounded" style="background:#f0f4f8; min-width:80px;">
                        <i class="bi bi-percent" style="color:#3DA8E8; font-size:20px;"></i>
                        <p class="mb-0" style="font-size:12px; font-weight:bold;">%{{ $product->kdv }}</p>
                        <p class="mb-0" style="font-size:11px; color:#999;">KDV</p>
                    </div>
                </div>

                <p style="font-size:14px; color:#555;">{{ $product->description }}</p>

                {{-- SEPETE EKLE --}}
                <div class="d-flex gap-2 mt-3">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button type="submit" class="btn w-100 text-white" style="background:#3DA8E8; padding:12px;">
                            <i class="bi bi-cart-plus"></i> Sepete Ekle
                        </button>
                    </form>
                    @auth
                        <form action="{{ route('favorite.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn" style="border:2px solid #1E3A5F; padding:12px 15px;">
                                <i class="bi bi-heart{{ $product->favorites()->where('user_id', Auth::id())->exists() ? '-fill' : '' }}" style="color:red; font-size:18px;"></i>
                            </button>
                        </form>
                    @endauth
                </div>

                {{-- KARGO BİLGİSİ --}}
                <div class="mt-3 p-3 rounded" style="background:#f0f4f8; font-size:13px;">
                    <p class="mb-1"><i class="bi bi-truck text-success"></i> 500₺ üzeri <strong>ücretsiz kargo</strong></p>
                    <p class="mb-1"><i class="bi bi-arrow-return-left text-primary"></i> <strong>14 gün</strong> içinde iade garantisi</p>
                    <p class="mb-0"><i class="bi bi-headset text-warning"></i> 7/24 müşteri desteği</p>
                </div>
            </div>
        </div>

        {{-- DETAYLI AÇIKLAMA --}}
        @if($product->detail)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header" style="background:#1E3A5F; color:white;">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Ürün Detayları</h6>
                </div>
                <div class="card-body">
                    {!! $product->detail !!}
                </div>
            </div>
        @endif

        {{-- YORUMLAR --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header" style="background:#1E3A5F; color:white;">
                <h6 class="mb-0"><i class="bi bi-chat-dots"></i> Müşteri Yorumları ({{ $product->reviews->count() }})</h6>
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('review.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <h6>Yorum Yaz</h6>
                        <div class="mb-2">
                            <label class="form-label">Puanınız</label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input type="radio" name="rating" value="{{ $i }}" class="form-check-input" id="star{{ $i }}" required>
                                        <label for="star{{ $i }}" class="form-check-label">{{ $i }} ⭐</label>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-2">
                            <textarea name="comment" class="form-control" rows="3" placeholder="Yorumunuzu yazın..."></textarea>
                        </div>
                        <button type="submit" class="btn text-white" style="background:#1E3A5F;">Yorum Gönder</button>
                    </form>
                @else
                    <p style="font-size:13px;"><a href="{{ route('customer.login') }}" style="color:#3DA8E8;">Giriş yapın</a> ve yorum yazın.</p>
                @endauth

                @forelse($product->reviews as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong style="font-size:14px;">{{ $review->user->name }}</strong>
                            <span style="font-size:12px; color:#999;">{{ $review->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="stars my-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <p style="font-size:13px; color:#555; margin-bottom:0;">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-muted" style="font-size:13px;">Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                @endforelse
            </div>
        </div>

        {{-- BENZER ÜRÜNLER --}}
        @if($relatedProducts->count() > 0)
            <div class="mt-5">
                <h5 style="color:#1E3A5F; font-weight:bold;"><i class="bi bi-grid"></i> Benzer Ürünler</h5>
                <div class="row g-3">
                    @foreach($relatedProducts as $product)
                        <div class="col-md-3 col-6">
                            <div class="product-card position-relative">
                                <a href="{{ route('shop.product.detail', $product->id) }}">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                                    @else
                                        <div style="height:200px; background:#f0f4f8; display:flex; align-items:center; justify-content:center;">
                                            <i class="bi bi-image" style="font-size:40px; color:#ccc;"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="card-body">
                                    <a href="{{ route('shop.product.detail', $product->id) }}" class="text-decoration-none">
                                        <p style="font-size:13px; color:#333; margin-bottom:5px; height:40px; overflow:hidden;">{{ $product->title }}</p>
                                    </a>
                                    <div class="price">{{ number_format($product->price, 2) }} ₺</div>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn-add-cart">
                                            <i class="bi bi-cart-plus"></i> Sepete Ekle
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
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

        function closeModal() { if (modalInstance) modalInstance.hide(); }
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
