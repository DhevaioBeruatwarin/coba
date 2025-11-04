<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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

    <main class="profile-page">
        <aside class="profile-sidebar">
            <div class="profile-menu-title">My Account</div>
            <ul class="profile-menu">
                <li><a href="#" class="active">Profile</a></li>
                <li><a href="#">Bank and Card</a></li>
                <li><a href="#">Address</a></li>
                <li><a href="#">Change Password</a></li>
                <li><a href="#">My Order</a></li>
            </ul>
        </aside>

        <section class="profile-card">
            <div class="profile-header-row">
                <h2 class="profile-title">My Profile</h2>
            </div>
            <div class="profile-content">
                <div class="profile-fields">
                    <div class="field-row">
                        <div class="field-label">Username</div>
                        <div class="field-value">{{ $pembeli->nama ?? $pembeli->name ?? '-' }}</div>
                        <div class="field-action">&nbsp;</div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">Email</div>
                        <div class="field-value">{{ $pembeli->email }}</div>
                        <div class="field-action">&nbsp;</div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">Phone Number</div>
                        <div class="field-value">{{ $pembeli->telepon ? substr($pembeli->telepon,0,3) . 'X - XXXX - XXXX' : '08XX - XXXX - XXXX' }}</div>
                        <div class="field-action">edit</div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">Gender</div>
                        <div class="field-value">
                            <div class="gender-group">
                                <label><input type="radio" name="gender" disabled> Male</label>
                                <label><input type="radio" name="gender" disabled> Female</label>
                            </div>
                        </div>
                        <div class="field-action">&nbsp;</div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">Date of Birth</div>
                        <div class="field-value">DD/MM/YYYY</div>
                        <div class="field-action">edit</div>
                    </div>
                    <div class="field-row">
                        <div class="field-label">Address</div>
                        <div class="field-value">{{ $pembeli->alamat ?? '-' }}</div>
                        <div class="field-action">edit</div>
                    </div>
                </div>
                <div class="avatar-card">
                    <div class="avatar-circle">👤</div>
                    <button class="btn-upload">Choose Image</button>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Jogja Artsphere</h3>
                <p>Tentang kami</p>
                <p>Blog/berita</p>
                <p>Promo Hari Ini</p>
                <p>Promo Guncang</p>
                <p>Karya Seni Asli Jogja</p>
                <p>Affiliate Program</p>
            </div>
            <div class="footer-section">
                <h3>Bantuan</h3>
                <p>Telepon<br>0823-5314-0</p>
                <p>Email<br>Customer.care@gmail.com</p>
                <div class="social-icons">
                    <div class="social-icon">f</div>
                    <div class="social-icon">t</div>
                    <div class="social-icon">in</div>
                    <div class="social-icon">ig</div>
                </div>
            </div>
            <div class="footer-section">
                <h3>Metode Pembayaran</h3>
                <div class="payment-methods">
                    <div class="payment-icon">BCA</div>
                    <div class="payment-icon">BRI</div>
                    <div class="payment-icon">VISA</div>
                    <div class="payment-icon">Gopay</div>
                    <div class="payment-icon">OVO</div>
                </div>
            </div>
            <div class="footer-section">
                <h3>Metode Pengiriman</h3>
                <div class="payment-methods">
                    <div class="payment-icon">JNE</div>
                    <div class="payment-icon">POS</div>
                    <div class="payment-icon">TIKI</div>
                    <div class="payment-icon">Sicepat</div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="footer-text">Jogja ArtSphere – Nyaman Belanja Online, Nyata dari Jogja. Jogja ArtSphere adalah platform belanja online kreatif yang menghadirkan karya orisinal dari seniman dan UMKM Jogja...</p>
            <div class="qr-code">QR</div>
        </div>
    </footer>
</body>
</html>


