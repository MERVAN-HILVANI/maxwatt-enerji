<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwatt Enerji - Admin Giriş</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0A1628, #1E3A5F);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .card-header {
            background: linear-gradient(135deg, #0A1628, #1E3A5F);
            border-radius: 15px 15px 0 0 !important;
        }
        .btn-primary {
            background-color: #1E7BC4;
            border-color: #1E7BC4;
        }
        .btn-primary:hover {
            background-color: #3DA8E8;
            border-color: #3DA8E8;
        }
        .form-control:focus {
            border-color: #3DA8E8;
            box-shadow: 0 0 0 0.2rem rgba(61,168,232,0.25);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center py-4">
                    <img src="{{ asset('images/logo-şefaf.png') }}" alt="Maxwatt Enerji" style="width: 200px;">
                    <p class="text-white-50 mb-0 mt-2">Admin Panel</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Adresi</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Şifre</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Beni Hatırla</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Giriş Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
