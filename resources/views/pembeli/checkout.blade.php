    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Konfirmasi Pembelian - Jogja Artsphere</title>
        <link rel="stylesheet" href="{{ asset('css/Seniman/karya/checkout.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="checkout-card">
                <div class="checkout-header">
                    <h2>Konfirmasi Pembelian</h2>
                    <p>Periksa kembali detail pesanan Anda</p>
                </div>

                <div class="checkout-body">
                    <!-- Alert Info -->
                    <div class="alert alert-info">
                        <span style="font-size: 20px;">ℹ️</span>
                        <span>Pastikan alamat dan produk sudah benar sebelum melanjutkan pembayaran</span>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="section">
                        <div class="section-title">Alamat Pengiriman</div>
                        <div class="address-box">
                            <div class="address-row">
                                <div class="address-label">Nama Penerima:</div>
                                <div class="address-value">{{ $pembeli->nama }}</div>
                            </div>
                            <div class="address-row">
                                <div class="address-label">No. Telepon:</div>
                                <div class="address-value">{{ $pembeli->no_hp }}</div>
                            </div>
                            <div class="address-row">
                                <div class="address-label">Alamat:</div>
                                <div class="address-value">{{ $pembeli->alamat }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Produk yang Dibeli -->
                    <div class="section">
                        <div class="section-title">Produk yang Dibeli ({{ count($produk) }} item)</div>
                        @foreach($produk as $item)
                        <div class="product-item">
                            <img src="{{ asset('storage/karya_seni/' . $item->karya->gambar) }}" 
                                alt="{{ $item->karya->judul }}"
                                class="product-image">
                            <div class="product-info">
                                <div class="product-name">{{ $item->karya->judul }}</div>
                                <div class="product-details">Kategori: {{ $item->karya->kategori ?? 'Seni Rupa' }}</div>
                                <div class="product-details">Jumlah: {{ $item->jumlah }} pcs</div>
                                <div class="product-details">Harga Satuan: Rp {{ number_format($item->karya->harga, 0, ',', '.') }}</div>
                                <div class="product-price">
                                    Subtotal: Rp {{ number_format($item->karya->harga * $item->jumlah, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Ringkasan Pembayaran -->
                    <div class="summary-box">
                        <div class="summary-row">
                            <span>Subtotal ({{ count($produk) }} produk):</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Admin:</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="summary-total">
                            <span>Total Pembayaran:</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <form method="POST" action="{{ route('pembeli.checkout.bayar') }}" id="checkoutForm">
                        @csrf
                        
                        @foreach($ids as $id)
                            <input type="hidden" name="ids[]" value="{{ $id }}">
                        @endforeach

                        <div class="btn-container">
                            <a href="{{ route('keranjang.index') }}" class="btn btn-secondary">
                                Kembali ke Keranjang
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnBayar">
                                Bayar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const btn = document.getElementById('btnBayar');
                btn.disabled = true;
                btn.textContent = 'Memproses...';
            });
        </script>
    </body>
    </html>