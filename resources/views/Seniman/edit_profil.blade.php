<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Seniman</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/profile.css') }}">
</head>
<body>
    <header class="seniman-header">
        <div class="header-left">
            <div class="logo">🎨</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            <a href="{{ route('seniman.profil') }}" class="back-link">Kembali ke Profil</a>
        </div>
    </header>

    <main class="profile-page">
        <section class="profile-card" style="margin: 0 auto; max-width: 700px;">
            <h2 class="profile-title">Edit Profil Seniman</h2>

            <form action="{{ route('seniman.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field-row">
                    <label class="field-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="input-field" value="{{ $seniman->nama }}" required>
                </div>

                <div class="field-row">
                    <label class="field-label">Email</label>
                    <input type="email" name="email" class="input-field" value="{{ $seniman->email }}" required>
                </div>

                <div class="field-row">
                    <label class="field-label">No. Telepon</label>
                    <input type="text" name="telepon" class="input-field" value="{{ $seniman->telepon }}">
                </div>

                <div class="field-row">
                    <label class="field-label">Bidang Seni</label>
                    <input type="text" name="bidang" class="input-field" value="{{ $seniman->bidang }}">
                </div>

                <div class="field-row">
                    <label class="field-label">Bio Singkat</label>
                    <textarea name="bio" class="input-field" rows="3">{{ $seniman->bio }}</textarea>
                </div>

                <div class="field-row">
                    <label class="field-label">Alamat</label>
                    <textarea name="alamat" class="input-field" rows="2">{{ $seniman->alamat }}</textarea>
                </div>

                <div style="text-align: center; margin-top: 25px;">
                    <button type="submit" class="btn-upload">Simpan Perubahan</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
    