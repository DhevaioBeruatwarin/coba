<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja Artsphere - Belanja Kerajinan & Seni</title>
    <link rel="stylesheet" href={{ asset('css/dashboard.css') }}>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="logo">🎭</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <input type="text" class="search-bar" placeholder="Search">
        <div class="header-right">
            <button class="icon-btn" id="camera-btn">📷</button>
            <button class="icon-btn">🛒</button>
            <button class="icon-btn">👤</button>
        </div>
    </header>

    <!-- Camera Modal -->
    <div id="camera-modal" class="camera-modal" style="display: none;">
        <div class="camera-container">
            <div class="camera-header">
                <h2>Akses Kamera</h2>
                <button class="close-btn" id="close-camera">&times;</button>
            </div>
            <video id="camera-video" autoplay playsinline></video>
            <div class="camera-controls">
                <button id="capture-btn" class="capture-btn">📸 Capture Photo</button>
                <button id="stop-camera-btn" class="stop-btn">✕ Close</button>
            </div>
            <div class="camera-status">
                <div class="status-dot"></div>
                <span>Camera Active</span>
            </div>
            <canvas id="camera-canvas" style="display: none;"></canvas>
            <img id="captured-image" style="display: none; max-width: 100%; margin-top: 24px;">
        </div>
    </div>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">Yuk belanja di Jogja Artsphere</h1>
            <p class="hero-subtitle">Temukan koleksi kerajinan tangan dan seni budaya dari Yogyakarta. Dari wayang kulit hingga batik, semua ada di sini!</p>
        </div>
        <div class="hero-image"></div>
    </div>

    <!-- Explore Popular Products -->
    <div class="section-title">
        <span>Explore popular product</span>
        <span class="see-all">See all</span>
    </div>

    <div class="product-section">
        <div class="product-grid">
            <div class="product-card">
                <div class="product-image">Gunung Merapi</div>
                <div class="product-info">
                    <div class="product-name">Gunung Merapi</div>
                    <div class="product-price">Rp 125.000</div>
                    <div class="product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Topeng Mask</div>
                <div class="product-info">
                    <div class="product-name">Topeng Mask</div>
                    <div class="product-price">Rp 610.000</div>
                    <div class="product-reviews">1 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Boneka Kayu</div>
                <div class="product-info">
                    <div class="product-name">Homemade Wooden Boneka</div>
                    <div class="product-price">Rp 185.000</div>
                    <div class="product-reviews">8 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Gunung Merapi</div>
                <div class="product-info">
                    <div class="product-name">Gunung Merapi</div>
                    <div class="product-price">Rp 125.000</div>
                    <div class="product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Gunung Merapi</div>
                <div class="product-info">
                    <div class="product-name">Gunung Merapi</div>
                    <div class="product-price">Rp 125.000</div>
                    <div class="product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Gunung Merapi</div>
                <div class="product-info">
                    <div class="product-name">Gunung Merapi</div>
                    <div class="product-price">Rp 125.000</div>
                    <div class="product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Gunung Merapi</div>
                <div class="product-info">
                    <div class="product-name">Gunung Merapi</div>
                    <div class="product-price">Rp 125.000</div>
                    <div class="product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Gunung Merapi</div>
                <div class="product-info">
                    <div class="product-name">Gunung Merapi</div>
                    <div class="product-price">Rp 125.000</div>
                    <div class="product-reviews">3 terjual</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Deals Section -->
    <div class="section-title">
        <span>Special deals hari ini</span>
    </div>

    <div class="special-deals">
        <div class="deals-left">
            <div class="deals-badge">100.100</div>
            <div class="deals-title">Special Deals</div>
            <div class="deals-discount">Diskon s.d.</div>
            <div class="deals-percent">0%</div>
        </div>
        <div class="deals-products">
            <div class="deals-product-card">
                <div class="deals-product-image"></div>
                <div class="deals-product-info">
                    <div class="deals-product-name">Gunung Merapi</div>
                    <div class="deals-product-price">Rp 125.000</div>
                    <div class="deals-product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="deals-product-card">
                <div class="deals-product-image"></div>
                <div class="deals-product-info">
                    <div class="deals-product-name">Gunung Merapi</div>
                    <div class="deals-product-price">Rp 125.000</div>
                    <div class="deals-product-reviews">3 terjual</div>
                </div>
            </div>

            <div class="deals-product-card">
                <div class="deals-product-image"></div>
                <div class="deals-product-info">
                    <div class="deals-product-name">Gunung Merapi</div>
                    <div class="deals-product-price">Rp 125.000</div>
                    <div class="deals-product-reviews">3 terjual</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Sale Section -->
    <div class="flash-sale">
        <div class="flash-header">
            <div class="flash-title">FLASH SALE</div>
            <div class="flash-timer">02 : 22 : 59</div>
        </div>
        <div class="flash-products">
            <div class="flash-product-card">
                <div class="flash-product-image"></div>
                <div class="flash-product-info">
                    <div class="flash-product-name">wayang kulit grogog</div>
                    <div class="flash-product-price">Rp 340.000</div>
                    <div class="flash-product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="flash-product-card">
                <div class="flash-product-image"></div>
                <div class="flash-product-info">
                    <div class="flash-product-name">wayang kulit grogog</div>
                    <div class="flash-product-price">Rp 340.000</div>
                    <div class="flash-product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="flash-product-card">
                <div class="flash-product-image"></div>
                <div class="flash-product-info">
                    <div class="flash-product-name">wayang kulit grogog</div>
                    <div class="flash-product-price">Rp 340.000</div>
                    <div class="flash-product-reviews">48 terjual</div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Products -->
    <div class="section-title">
        <span>More products</span>
    </div>

    <div class="more-products">
        <div class="more-grid">
            <div class="product-card">
                <div class="product-image">Topeng Mask</div>
                <div class="product-info">
                    <div class="product-name">Topeng Mask</div>
                    <div class="product-price">Rp 610.000</div>
                    <div class="product-reviews">1 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Boneka Kayu</div>
                <div class="product-info">
                    <div class="product-name">Homemade Wooden Boneka</div>
                    <div class="product-price">Rp 185.000</div>
                    <div class="product-reviews">8 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Topeng Mask</div>
                <div class="product-info">
                    <div class="product-name">Topeng Mask</div>
                    <div class="product-price">Rp 610.000</div>
                    <div class="product-reviews">1 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Wayang Kulit</div>
                <div class="product-info">
                    <div class="product-name">wayang kulit grogog</div>
                    <div class="product-price">Rp 340.000</div>
                    <div class="product-reviews">48 terjual</div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">Topeng Mask</div>
                <div class="product-info">
                    <div class="product-name">Topeng Mask</div>
                    <div class="product-price">Rp 610.000</div>
                    <div class="product-reviews">1 terjual</div>
                </div>
            </div>
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
                <p>Karya Seni Asli Jogja</p>
                <p>Produk Berkualitas Tinggi</p>
                <div class="social-icons">
                    <div class="social-icon">f</div>
                    <div class="social-icon">𝕏</div>
                    <div class="social-icon">📷</div>
                    <div class="social-icon">▶</div>
                </div>
            </div>

            <div class="footer-section">
                <h3>Bantuan</h3>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Hubungi Kami</a>
                    <a href="#">Email</a>
                    <a href="#">Customer Service</a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Metode Pembayaran</h3>
                <div class="payment-methods">
                    <div class="payment-icon">BCA</div>
                    <div class="payment-icon">BNI</div>
                    <div class="payment-icon">VISA</div>
                </div>
            </div>

            <div class="footer-section">
                <h3>Metode Pengiriman</h3>
                <div class="payment-methods">
                    <div class="payment-icon">JNE</div>
                    <div class="payment-icon">POS</div>
                    <div class="payment-icon">GRAB</div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-text">
                <p><strong>Jogja Artsphere</strong> - Nyaman Belanja Online, Nyata dari Joga</p>
                <p>Jogja Artsphere adalah platform belanja online terpercaya yang menyediakan karya seni dan kerajinan tangan berkualitas dari Yogyakarta. Kami berkomitmen untuk memberikan pengalaman berbelanja yang menyenangkan dengan produk autentik dan layanan pelanggan terbaik.</p>
                <p>Dapatkan pengalaman berbelanja yang tak terlupakan dengan koleksi eksklusif kami. Setiap produk dipilih dengan cermat untuk memastikan kualitas terbaik. Nikmati kemudahan pembayaran dan pengiriman cepat ke seluruh Indonesia.</p>
                <p>Jogja Artsphere - Satu Jika Untuk Koleksi Karya Lokal.</p>
            </div>
            <div class="qr-code">QR CODE</div>
        </div>
    </footer>

    <script>
        let cameraStream = null;

        // Tunggu DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            const cameraBtn = document.getElementById('camera-btn');
            const cameraModal = document.getElementById('camera-modal');
            const video = document.getElementById('camera-video');
            const closeBtn = document.getElementById('close-camera');
            const stopCameraBtn = document.getElementById('stop-camera-btn');
            const captureBtn = document.getElementById('capture-btn');

            console.log('Camera Button:', cameraBtn);
            console.log('Camera Modal:', cameraModal);

            if (!cameraBtn) {
                console.error('Camera button tidak ditemukan!');
                return;
            }

            // Buka modal kamera
            cameraBtn.addEventListener('click', function() {
                console.log('Tombol kamera diklik');
                if (cameraModal) {
                    cameraModal.style.display = 'flex';
                    console.log('Modal dibuka');
                    setTimeout(startCamera, 100);
                }
            });

            // Tutup modal
            if (closeBtn) {
                closeBtn.addEventListener('click', stopCamera);
            }
            if (stopCameraBtn) {
                stopCameraBtn.addEventListener('click', stopCamera);
            }

            // Fungsi mulai kamera
            function startCamera() {
                console.log('Mulai akses kamera...');
                
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('❌ Browser Anda tidak mendukung akses kamera');
                    return;
                }

                navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    }
                })
                .then(stream => {
                    console.log('Kamera berhasil diakses');
                    cameraStream = stream;
                    if (video) {
                        video.srcObject = stream;
                        video.onloadedmetadata = function() {
                            video.play();
                        };
                    }
                })
                .catch(error => {
                    console.error('Error akses kamera:', error);
                    alert('❌ Error: ' + error.name + '\n' + error.message);
                    stopCamera();
                });
            }

            // Fungsi stop kamera
            function stopCamera() {
                console.log('Hentikan kamera');
                if (cameraStream) {
                    const tracks = cameraStream.getTracks();
                    tracks.forEach(track => track.stop());
                    cameraStream = null;
                }
                if (video) {
                    video.srcObject = null;
                }
                if (cameraModal) {
                    cameraModal.style.display = 'none';
                }
            }

            // Ambil foto
            if (captureBtn) {
                captureBtn.addEventListener('click', function() {
                    console.log('Ambil foto');
                    try {
                        const canvas = document.getElementById('camera-canvas');
                        const ctx = canvas.getContext('2d');
                        
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        
                        if (canvas.width === 0 || canvas.height === 0) {
                            alert('⏳ Kamera belum siap, tunggu sebentar...');
                            return;
                        }
                        
                        ctx.drawImage(video, 0, 0);
                        
                        const image = document.getElementById('captured-image');
                        const photoData = canvas.toDataURL('image/jpeg');
                        image.src = photoData;
                        image.style.display = 'block';
                        
                        // Download otomatis
                        const link = document.createElement('a');
                        link.href = photoData;
                        const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
                        link.download = `foto-${timestamp}.jpg`;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        alert('✅ Foto berhasil diambil dan diunduh!');
                    } catch (error) {
                        console.error('Error:', error);
                        alert('❌ Error mengambil foto: ' + error.message);
                    }
                });
            }
        });
    </script>
</body>
</html>