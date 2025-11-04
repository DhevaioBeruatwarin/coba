<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .profile-container { max-width: 960px; margin: 40px auto; padding: 24px; background: #fff; border-radius: 12px; box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
        .profile-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .profile-grid { display: grid; grid-template-columns: 180px 1fr; gap: 24px; align-items: center; }
        .avatar { width: 160px; height: 160px; border-radius: 50%; background: #ececec; display:flex; align-items:center; justify-content:center; font-size: 56px; }
        .label { color: #666; font-size: 14px; }
        .value { font-size: 16px; font-weight: 600; }
        .row { display:grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .row:last-child { border-bottom: none; }
        .back-link { text-decoration:none; padding:8px 14px; background:#111; color:#fff; border-radius:8px; }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <div class="logo">#</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            <a href="{{ route('pembeli.dashboard') }}" class="back-link">Kembali</a>
        </div>
    </header>

    <div class="profile-container">
        <div class="profile-header">
            <h2>Profil Pembeli</h2>
        </div>
        <div class="profile-grid">
            <div class="avatar">👤</div>
            <div>
                <div class="row">
                    <div class="label">Nama</div>
                    <div class="value">{{ $pembeli->nama ?? $pembeli->name ?? '-' }}</div>
                </div>
                <div class="row">
                    <div class="label">Email</div>
                    <div class="value">{{ $pembeli->email }}</div>
                </div>
                <div class="row">
                    <div class="label">Telepon</div>
                    <div class="value">{{ $pembeli->telepon ?? '-' }}</div>
                </div>
                <div class="row">
                    <div class="label">Alamat</div>
                    <div class="value">{{ $pembeli->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


