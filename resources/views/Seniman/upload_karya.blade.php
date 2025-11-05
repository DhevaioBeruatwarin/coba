<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Karya</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/dashboard.css') }}">
</head>
<body>
    <header class="seniman-header">
        <div class="header-left">
            <div class="logo">🎨</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            <a href="{{ route('seniman.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
        </div>
    </header>

    <main class="profile-page">
        <section class="profile-card" style="margin: 0 auto; max-width: 700px;">
            <h2 class="profile-title">Upload Karya Baru</h2>

            <form action="{{ route('seniman.store_karya') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field-row">
                    <label class="field-label">Judul Karya</label>
                    <input type="text" name="judul" class="input-field" required>
                </div>

                <div class="field-row">
                    <label class="field-label">Deskripsi</label>
                    <textarea name="deskripsi" class="input-field" rows="4" placeholder="Tuliskan inspirasi dan detail karya Anda..."></textarea>
                </div>

                <div class="field-row">
                    <label class="field-label">Kategori</label>
                    <select name="kategori" class="input-field">
                        <option value="lukisan">Lukisan</option>
                        <option value="patung">Patung</option>
                        <option value="digital">Digital Art</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="field-row">
                    <label class="field-label">Harga (Rp)</label>
                    <input type="number" name="harga" class="input-field" min="0">
                </div>

                <div class="field-row">
                    <label class="field-label">Upload Gambar</label>
                    <input type="file" name="gambar" class="input-field" required>
                </div>

                <div style="text-align: center; margin-top: 25px;">
                    <button type="submit" class="btn-upload">Unggah Karya</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
