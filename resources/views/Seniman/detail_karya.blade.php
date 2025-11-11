<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $karya->nama_karya }} - Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/detail_karya.css') }}">
</head>
<body>
    <!-- Header -->
 <header style="background-color:#231105; color:#fff; display:flex; align-items:center; justify-content:space-between; padding:10px 20px; position:sticky; top:0; z-index:1000;">
    <div style="display:flex; align-items:center; gap:10px;">
        <div class="logo">
            <img src="{{ asset('assets/logo.png') }}" 
                 alt="Jogja Artsphere Logo" 
                 style="width:45px; height:45px; object-fit:contain;">
        </div>
        <div style="font-weight:bold; font-size:18px; color:#fff;">JOGJA ARTSPHERE</div>
    </div>

    <div style="flex:1; margin:0 20px;">
         <form action="{{ route('dashboard.seniman.search') }}" method="GET" style="display:inline;">
    <input type="text" name="query" class="search-bar" placeholder="Cari karya..." value="{{ request('query') }}">
</form>
    </div>

    <div style="display:flex; align-items:center; gap:15px;">
        <button style="font-size:18px; background:none; border:none; cursor:pointer; color:#fff;">🛒</button>
        @php
            $pembeli = Auth::guard('pembeli')->user();
            $fotoPath = $pembeli->foto 
                ? asset('storage/foto_pembeli/' . $pembeli->foto)
                : asset('assets/defaultprofile.png');
        @endphp
        <a href="{{ route('pembeli.profil') }}">
            <img src="{{ $fotoPath }}" alt="Profil" 
                 style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #ffffffff;">
        </a>
    </div>
</header>


    <!-- Main PDP -->
    <div class="pdp-container">
        <!-- Gambar Produk -->
        <div class="pdp-left">
            @if($karya->gambar)
                <img id="mainImg" src="{{ asset('storage/karya_seni/' . $karya->gambar) }}" alt="{{ $karya->nama_karya }}">
            @else
                <div class="no-image">No Image Available</div>
            @endif
        </div>

        <!-- Info Produk -->
        <div class="pdp-right">
            <h1>{{ $karya->nama_karya }}</h1>

            <div class="rating">
                @if($averageRating)
                    ⭐ {{ $averageRating }} ({{ $karya->reviews->count() }} reviews)
                @else
                    Belum ada rating
                @endif
            </div>

            <div class="price">Rp{{ number_format($karya->harga,0,',','.') }}</div>

            <p class="author">By {{ $karya->seniman->nama ?? 'Unknown' }}</p>

            <p class="description">{{ $karya->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>

            <div class="actions">
                <button class="btn-cart">Tambahkan ke Keranjang</button>
                <button class="btn-buy">Beli Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <section class="related-products">
        <h2>Produk Lain dari Seniman Ini</h2>
        <div class="grid">
            @foreach($karyaSeniman as $item)
                <a href="{{ route('karya.detail', $item->kode_seni) }}" class="card">
                    @if($item->gambar)
                        <img src="{{ asset('storage/karya_seni/' . $item->gambar) }}" alt="{{ $item->nama_karya }}">
                    @else
                        <div class="no-image">No Image</div>
                    @endif
                    <p class="title">{{ $item->nama_karya }}</p>
                    <p class="price">Rp {{ number_format($item->harga,0,',','.') }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Jogja Artsphere</h3>
                <p>Jl. Malioboro No.123, Yogyakarta 55271</p>
                <p>Telp: (0274) 123-4567</p>
                <p>Email: info@jogja-artsphere.com</p>
            </div>
            <div class="footer-section">
                <h3>Bantuan</h3>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">Customer Service</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p><strong>Jogja Artsphere</strong> - Satu Jika Untuk Koleksi Karya Lokal.</p>
        </div>
    </footer>

    <script>
        function changeImage(thumb) {
            document.getElementById('mainImg').src = thumb.src;
        }
    </script>
</body>
</html>
