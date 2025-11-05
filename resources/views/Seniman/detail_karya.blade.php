<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Karya - {{ $karya->judul }}</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/dashboard.css') }}">
</head>
<body>
    <header class="seniman-header">
        <div class="header-left">
            <div class="logo">🎨</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            <a href="{{ route('seniman.dashboard') }}" class="back-link">Kembali</a>
        </div>
    </header>

    <main class="profile-page">
        <section class="profile-card" style="margin: 0 auto; max-width: 900px;">
            <h2 class="profile-title">Detail Karya</h2>

            <div class="karya-detail-container">
                <div class="karya-image">
                    <img src="{{ asset('uploads/' . $karya->gambar) }}" alt="{{ $karya->judul }}">
                </div>
                <div class="karya-info">
                    <h3>{{ $karya->judul }}</h3>
                    <p class="karya-deskripsi">{{ $karya->deskripsi }}</p>
                    <p><strong>Kategori:</strong> {{ ucfirst($karya->kategori) }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($karya->harga, 0, ',', '.') }}</p>
                    <p><strong>Tanggal Upload:</strong> {{ $karya->created_at->format('d M Y') }}</p>

                    <div style="margin-top: 20px;">
                        <a href="{{ route('seniman.edit_karya', $karya->id) }}" class="btn-upload">Edit Karya</a>
                        <form action="{{ route('seniman.delete_karya', $karya->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-upload" style="background:#c0392b;color:white;">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
