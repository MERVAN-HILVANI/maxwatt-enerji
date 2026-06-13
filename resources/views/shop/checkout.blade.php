@extends('layouts.shop')

@section('title') Sipariş Oluştur @endsection

@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#3DA8E8;">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" style="color:#3DA8E8;">Sepetim</a></li>
                    <li class="breadcrumb-item active">Sipariş Oluştur</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4">
        <h4 style="color:#1E3A5F; font-weight:bold;"><i class="bi bi-credit-card"></i> Sipariş Oluştur</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(!Auth::user()->isEmailVerified())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>E-posta adresiniz doğrulanmamış!</strong>
                Sipariş verebilmek için e-posta adresinizi doğrulamanız gerekmektedir.
                <a href="#" class="btn btn-sm btn-warning ms-2">Doğrulama E-postası Gönder</a>
            </div>
        @endif

        <div class="row">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Teslimat Bilgileri</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', Auth::user()->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefon <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control"
                                           value="{{ old('phone', Auth::user()->phone) }}" required
                                           placeholder="05XX XXX XX XX">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">E-posta <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', Auth::user()->email) }}" required>
                                </div>

                                {{-- İL --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">İl <span class="text-danger">*</span></label>
                                    <select name="city" id="city" class="form-select" required onchange="getIlceler()">
                                        <option value="">İl Seçin</option>
                                    </select>
                                </div>

                                {{-- İLÇE --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">İlçe <span class="text-danger">*</span></label>
                                    <select name="district" id="district" class="form-select" required onchange="getMahalleler()" disabled>
                                        <option value="">Önce İl Seçin</option>
                                    </select>
                                </div>

                                {{-- MAHALLE --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mahalle <span class="text-danger">*</span></label>
                                    <select name="mahalle" id="mahalle" class="form-select" required disabled>
                                        <option value="">Önce İlçe Seçin</option>
                                    </select>
                                </div>

                                {{-- POSTA KODU --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Posta Kodu</label>
                                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                                           value="{{ old('zip_code') }}" placeholder="Otomatik dolar">
                                </div>

                                {{-- SOKAK --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cadde / Sokak <span class="text-danger">*</span></label>
                                    <input type="text" name="sokak" class="form-control" required
                                           value="{{ old('sokak') }}" placeholder="Cadde veya sokak adı">
                                </div>

                                {{-- APARTMAN --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Apartman <span class="text-danger">*</span></label>
                                    <input type="text" name="apartman" class="form-control" required
                                           value="{{ old('apartman') }}" placeholder="Apartman adı">
                                </div>

                                {{-- DAİRE --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Daire No <span class="text-danger">*</span></label>
                                    <input type="text" name="daire" class="form-control" required
                                           value="{{ old('daire') }}" placeholder="Daire no">
                                </div>

                                {{-- TAM ADRES --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Tam Adres <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control" rows="3" required
                                              placeholder="Adres otomatik dolar...">{{ old('address', Auth::user()->address) }}</textarea>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Sipariş Notu</label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="İsteğe bağlı..."></textarea>
                                </div>
                            </div>

                            <hr>
                            <h6>Ödeme Yöntemi <span class="text-danger">*</span></h6>

                            {{-- KAPIDA ÖDEME --}}
                            <div class="form-check mb-2">
                                <input type="radio" name="payment_method" value="cash_on_delivery"
                                       class="form-check-input" id="cod" checked onchange="showPaymentInfo()">
                                <label for="cod" class="form-check-label">
                                    <i class="bi bi-cash-coin"></i> Kapıda Ödeme
                                    <small class="text-muted">(Max 5.000₺, yeni müşteri max 1.000₺)</small>
                                </label>
                            </div>

                            {{-- BANKA HAVALESİ --}}
                            <div class="form-check mb-2">
                                <input type="radio" name="payment_method" value="bank_transfer"
                                       class="form-check-input" id="bank" onchange="showPaymentInfo()">
                                <label for="bank" class="form-check-label">
                                    <i class="bi bi-bank"></i> Banka Havalesi / EFT
                                </label>
                            </div>

                            {{-- KREDİ KARTI --}}
                            <div class="form-check mb-3">
                                <input type="radio" name="payment_method" value="credit_card"
                                       class="form-check-input" id="credit" onchange="showPaymentInfo()">
                                <label for="credit" class="form-check-label">
                                    <i class="bi bi-credit-card"></i> Kredi Kartı
                                </label>
                            </div>

                            {{-- KAPIDA ÖDEME BİLGİLERİ --}}
                            <div id="cod_info" class="p-3 rounded mb-3" style="background:#f0f4f8;">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Kapıda ödeme için kimlik bilgileriniz zorunludur.
                                    Admin onayından sonra siparişiniz hazırlanacaktır.
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">TC Kimlik No <span class="text-danger">*</span></label>
                                        <input type="text" name="tc_kimlik" class="form-control"
                                               placeholder="11 haneli TC Kimlik No" maxlength="11"
                                               value="{{ old('tc_kimlik') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Doğum Tarihi <span class="text-danger">*</span></label>
                                        <input type="date" name="dogum_tarihi" class="form-control"
                                               value="{{ old('dogum_tarihi') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- BANKA BİLGİLERİ --}}
                            <div id="bank_info" style="display:none;" class="p-3 rounded mb-3">
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-bank"></i> Banka Hesap Bilgileri</h6>
                                    <p class="mb-1"><strong>Banka:</strong> Ziraat Bankası</p>
                                    <p class="mb-1"><strong>Hesap Sahibi:</strong> Maxwatt Enerji A.Ş.</p>
                                    <p class="mb-1"><strong>IBAN:</strong> TR12 0001 0012 3456 7890 1234 56</p>
                                    <p class="mb-0 text-danger"><strong>⚠️ Açıklama:</strong> Sipariş numaranızı açıklamaya yazmayı unutmayın!</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Ödeme Dekontu <span class="text-danger">*</span></strong></label>
                                    <input type="file" name="payment_receipt" id="payment_receipt"
                                           class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">PDF, JPG veya PNG. Max: 5MB</small>
                                </div>
                            </div>

                            {{-- KREDİ KARTI BİLGİLERİ --}}
                            <div id="credit_info" style="display:none;" class="p-3 rounded mb-3" style="background:#f0f4f8;">
                                <div class="alert alert-warning">
                                    <i class="bi bi-info-circle"></i> Bu demo projedir, gerçek ödeme alınmamaktadır.
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Kart Numarası <span class="text-danger">*</span></label>
                                        <input type="text" name="card_number" class="form-control"
                                               placeholder="1234 5678 9012 3456" maxlength="19"
                                               oninput="formatCardNumber(this)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kart Sahibi <span class="text-danger">*</span></label>
                                        <input type="text" name="card_name" class="form-control"
                                               placeholder="Ad Soyad">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Son Kullanma <span class="text-danger">*</span></label>
                                        <input type="text" name="card_expiry" class="form-control"
                                               placeholder="MM/YY" maxlength="5" oninput="formatExpiry(this)">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">CVV <span class="text-danger">*</span></label>
                                        <input type="text" name="card_cvv" class="form-control"
                                               placeholder="123" maxlength="3">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 text-white" style="background:#3DA8E8; padding:12px;">
                                <i class="bi bi-check-circle"></i> Siparişi Onayla
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background:#1E3A5F; color:white;">
                        <h6 class="mb-0">Sipariş Özeti</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                         width="50" height="50" style="object-fit:cover; border-radius:5px;">
                                @endif
                                <div class="ms-2 flex-grow-1">
                                    <p class="mb-0" style="font-size:12px; font-weight:bold;">{{ $item->product->title }}</p>
                                    <p class="mb-0" style="font-size:12px; color:#999;">{{ $item->quantity }} adet</p>
                                </div>
                                <strong style="font-size:13px; color:#1E3A5F;">
                                    {{ number_format($item->product->discounted_price * $item->quantity, 2) }} ₺
                                </strong>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;">Ara Toplam</span>
                            <span style="font-size:13px;">{{ number_format($total, 2) }} ₺</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;">KDV</span>
                            <span style="font-size:13px;">{{ number_format($totalKdv, 2) }} ₺</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;">Kargo</span>
                            <span style="font-size:13px; color:green;">{{ $total >= 500 ? 'Ücretsiz' : '29.90 ₺' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Toplam</strong>
                            <strong style="color:#1E3A5F; font-size:16px;">
                                {{ number_format($total + $totalKdv + ($total >= 500 ? 0 : 29.90), 2) }} ₺
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showPaymentInfo() {
            const cod = document.getElementById('cod').checked;
            const bank = document.getElementById('bank').checked;
            const credit = document.getElementById('credit').checked;

            document.getElementById('cod_info').style.display = cod ? 'block' : 'none';
            document.getElementById('bank_info').style.display = bank ? 'block' : 'none';
            document.getElementById('credit_info').style.display = credit ? 'block' : 'none';
        }

        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            input.value = value;
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0,2) + '/' + value.substring(2,4);
            }
            input.value = value;
        }

        fetch('https://turkiyeapi.dev/api/v1/provinces')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('city');
                data.data.forEach(il => {
                    const option = document.createElement('option');
                    option.value = il.name;
                    option.dataset.id = il.id;
                    option.textContent = il.name;
                    select.appendChild(option);
                });
            });

        function getIlceler() {
            const citySelect = document.getElementById('city');
            const selectedOption = citySelect.options[citySelect.selectedIndex];
            const ilId = selectedOption.dataset.id;
            const districtSelect = document.getElementById('district');
            const mahalleSelect = document.getElementById('mahalle');

            districtSelect.innerHTML = '<option value="">İlçe Yükleniyor...</option>';
            districtSelect.disabled = true;
            mahalleSelect.innerHTML = '<option value="">Önce İlçe Seçin</option>';
            mahalleSelect.disabled = true;

            fetch(`https://turkiyeapi.dev/api/v1/provinces/${ilId}`)
                .then(res => res.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">İlçe Seçin</option>';
                    data.data.districts.forEach(ilce => {
                        const option = document.createElement('option');
                        option.value = ilce.name;
                        option.dataset.id = ilce.id;
                        option.textContent = ilce.name;
                        districtSelect.appendChild(option);
                    });
                    districtSelect.disabled = false;
                });
            updateAddress();
        }

        function getMahalleler() {
            const districtSelect = document.getElementById('district');
            const selectedOption = districtSelect.options[districtSelect.selectedIndex];
            const ilceId = selectedOption.dataset.id;
            const mahalleSelect = document.getElementById('mahalle');

            mahalleSelect.innerHTML = '<option value="">Mahalle Yükleniyor...</option>';
            mahalleSelect.disabled = true;

            fetch(`https://turkiyeapi.dev/api/v1/districts/${ilceId}`)
                .then(res => res.json())
                .then(data => {
                    mahalleSelect.innerHTML = '<option value="">Mahalle Seçin</option>';
                    if (data.data.neighborhoods) {
                        data.data.neighborhoods.forEach(mahalle => {
                            const option = document.createElement('option');
                            option.value = mahalle.name;
                            option.dataset.zip = mahalle.zipCode || '';
                            option.textContent = mahalle.name;
                            mahalleSelect.appendChild(option);
                        });
                    }
                    mahalleSelect.disabled = false;
                });
            updateAddress();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('mahalle').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const zipCode = selectedOption.dataset.zip;
                if (zipCode) {
                    document.getElementById('zip_code').value = zipCode;
                }
                updateAddress();
            });

            ['sokak', 'apartman', 'daire'].forEach(field => {
                const el = document.querySelector(`[name="${field}"]`);
                if (el) el.addEventListener('input', updateAddress);
            });
        });

        function updateAddress() {
            const il = document.getElementById('city').value;
            const ilce = document.getElementById('district').value;
            const mahalle = document.getElementById('mahalle').value;
            const sokak = document.querySelector('[name="sokak"]').value;
            const apartman = document.querySelector('[name="apartman"]').value;
            const daire = document.querySelector('[name="daire"]').value;

            let adres = '';
            if (mahalle) adres += mahalle + ' Mah. ';
            if (sokak) adres += sokak + ' ';
            if (apartman) adres += apartman + ' Apt. ';
            if (daire) adres += 'No: ' + daire + ' ';
            if (ilce) adres += ilce + ' / ';
            if (il) adres += il;

            if (adres.trim()) {
                document.getElementById('address').value = adres.trim();
            }
        }
    </script>
@endsection
