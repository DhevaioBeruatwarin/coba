<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Seniman - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="logo">🎨</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <input type="text" class="search-bar" placeholder="Cari karya seni...">
        <div class="header-right">
            @if(\Illuminate\Support\Facades\Auth::guard('seniman')->check())
                <a class="icon-btn" href="{{ route('seniman.profil') }}" title="Profil">👤</a>
            @endif
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">Selamat datang, {{ Auth::guard('seniman')->user()->nama }}</h1>
            <p class="hero-subtitle">Temukan karya seni dari seniman lain dan bagikan inspirasimu di Jogja Artsphere.</p>
        </div>
        <div class="hero-image"></div>
    </div>

    <!-- Section: Explore All Karya -->
    <div class="section-title">
        <span>Explore karya seni di platform</span>
    </div>

    <div class="product-section">
        <div class="product-grid">
            @if($karyaSeni->isEmpty())
                <p style="text-align:center; width:100%; margin-top:20px; color:gray;">Belum ada karya seni yang ditampilkan.</p>
            @else
                @foreach($karyaSeni as $item)
                    <div class="product-card">
                        <div class="product-image">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_karya }}" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
                            @else
                                <div style="width:100%; height:200px; background:#ddd; display:flex; align-items:center; justify-content:center; border-radius:10px;">No Image</div>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ $item->nama_karya }}</div>
                            <div class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                            <div class="product-reviews">{{ $item->terjual ?? 0 }} terjual</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Section: Karya Saya -->
    <div class="section-title">
        <span>Karya saya</span>
    </div>

    <div class="product-section">
        <div class="product-grid">
            @php
                $myWorks = $karyaSeni->where('id_seniman', Auth::guard('seniman')->user()->id_seniman);
            @endphp

            @if($myWorks->isEmpty())
                <p style="text-align:center; width:100%; margin-top:20px; color:gray;">Kamu belum menambahkan karya seni.</p>
            @else
                @foreach($myWorks as $karya)
                    <div class="product-card">
                        <div class="product-image">
                            @if($karya->gambar)
                                <img src="{{ asset('storage/' . $karya->gambar) }}" alt="{{ $karya->nama_karya }}" style="width:100%; height:200px; object-fit:cover; border-radius:10px;">
                            @else
                                <div style="width:100%; height:200px; background:#ddd; display:flex; align-items:center; justify-content:center; border-radius:10px;">No Image</div>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ $karya->nama_karya }}</div>
                            <div class="product-price">Rp {{ number_format($karya->harga, 0, ',', '.') }}</div>
                            <div class="product-reviews">{{ $karya->terjual ?? 0 }} terjual</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Jogja Artsphere</h3>
                <p>Jl. Malioboro No. 123</p>
                <p>Yogyakarta 55271</p>
                <p>Telp: (0274) 123-4567</p>
                <p>Email: info@jogja-artsphere.com</p>
            </div>

            <div class="footer-section">
                <h3>Informasi</h3>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Pusat Bantuan</a>
                    <a href="#">Kebijakan Privasi</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p><strong>Jogja Artsphere</strong> - Rumah bagi kreativitas lokal Yogyakarta.</p>
        </div>
    </footer>
</body>
</html>
