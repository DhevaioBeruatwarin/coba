<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Seniman</title>
    <link rel="stylesheet" href="{{ asset('css/Seniman/profile.css') }}">
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
        <aside class="profile-sidebar">
            <div class="profile-menu-title">Menu Seniman</div>
            <ul class="profile-menu">
                <li><a href="{{ route('seniman.profile') }}" class="active">Profil</a></li>
                <li><a href="{{ route('seniman.detail_karya') }}">Karya Saya</a></li>
                <li><a href="{{ route('seniman.upload_karya') }}">Upload Karya</a></li>
                <li><a href="{{ route('seniman.edit_profil') }}">Edit Profil</a></li>
                <li><a href="{{ route('logout') }}">Keluar</a></li>
            </ul>
        </aside>

        <section class="profile-card">
            <div class="profile-header-row">
                <h2 class="profile-title">Profil Seniman</h2>
            </div>
            <div class="profile-content">
                <div class="profile-fields">
                    <div class="field-row">
                        <div class="field-label">Nama Lengkap</div>
                        <div class="field-value">{{ $seniman->nama }}</div>
                        <div class="field-action">&nbsp;</div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Email</div>
                        <div class="field-value">{{ $seniman->email }}</div>
                        <div class="field-action">&nbsp;</div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">No. Telepon</div>
                        <div class="field-value">{{ $seniman->telepon ?? '-' }}</div>
                        <div class="field-action">&nbsp;</div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Bidang Seni</div>
                        <div class="field-value">{{ $seniman->bidang ?? 'Belum diisi' }}</div>
                        <div class="field-action">edit</div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Bio Singkat</div>
                        <div class="field-value">{{ $seniman->bio ?? 'Belum ada deskripsi' }}</div>
                        <div class="field-action">edit</div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Alamat</div>
                        <div class="field-value">{{ $seniman->alamat ?? '-' }}</div>
                        <div class="field-action">edit</div>
                    </div>
                </div>

                <div class="avatar-card">
                    <div class="avatar-circle">
                        @if($seniman->foto)
                            <img src="{{ asset('uploads/' . $seniman->foto) }}" alt="Foto Seniman">
                        @else
                            👤
                        @endif
                    </div>
                    <form action="{{ route('seniman.update_foto', $seniman->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="file" name="foto" id="foto" hidden onchange="this.form.submit()">
                        <label for="foto" class="btn-upload">Ubah Foto</label>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="seniman-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Jogja Artsphere</h3>
                <p>Tentang Kami</p>
                <p>Blog / Berita</p>
                <p>Promo & Event</p>
                <p>Karya Asli Jogja</p>
            </div>
            <div class="footer-section">
                <h3>Kontak Kami</h3>
                <p>Telepon<br>0823-5314-0</p>
                <p>Email<br>support@jogjaartsphere.com</p>
            </div>
            <div class="footer-section">
                <h3>Ikuti Kami</h3>
                <div class="social-icons">
                    <div class="social-icon">f</div>
                    <div class="social-icon">ig</div>
                    <div class="social-icon">yt</div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Jogja Artsphere — Platform Seniman Jogja</p>
        </div>
    </footer>
</body>
</html>
