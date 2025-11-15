<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Seniman/editprofile.css') }}">
    <title>Edit Profil - Jogja Artsphere</title>
    
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" 
                     alt="Jogja Artsphere Logo">
            </div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        
        <form action="{{ route('dashboard.pembeli.search') }}" method="GET" style="display:inline;">
            <input type="text" name="query" class="search-bar" placeholder="Cari karya..." value="{{ request('query') }}">
        </form>
        
        <div class="header-right">
            <a href="{{ route('pembeli.profil') }}" class="back-link">Kembali ke Profil</a>
            
            @if(\Illuminate\Support\Facades\Auth::guard('pembeli')->check())
                @php
                    $pembeli = Auth::guard('pembeli')->user();
                    $fotoPath = $pembeli->foto 
                        ? asset('storage/foto_pembeli/' . $pembeli->foto)
                        : asset('assets/defaultprofile.png');
                @endphp

                <a href="{{ route('pembeli.profil') }}" title="Profil">
                    <img src="{{ $fotoPath }}" 
                         alt="Foto Profil"
                         class="profile-icon">
                </a>
            @endif
        </div>
    </header>

    <!-- Main Content -->
    <main class="profile-page">
        <section class="profile-card">
            <h2 class="profile-title">Edit Profil</h2>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Terjadi Kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Box -->
            <div class="info-box">
                <strong>Informasi Penting</strong>
                Nama dan Email tidak dapat diubah. Hubungi admin jika perlu melakukan perubahan.
            </div>

            <!-- Form -->
            <form action="{{ route('pembeli.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap (Read-only) -->
                <div class="field-row">
                    <label class="field-label">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="nama" 
                        class="input-field" 
                        value="{{ $pembeli->nama }}" 
                        readonly
                    >
                    <div class="helper-text">Field ini tidak dapat diubah</div>
                </div>

                <!-- Email (Read-only) -->
                <div class="field-row">
                    <label class="field-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="input-field" 
                        value="{{ $pembeli->email }}" 
                        readonly
                    >
                    <div class="helper-text">Field ini tidak dapat diubah</div>
                </div>

                <!-- No. Telepon -->
                <div class="field-row">
                    <label class="field-label">No. Telepon</label>
                    <input 
                        type="text" 
                        name="no_hp" 
                        class="input-field" 
                        value="{{ $pembeli->no_hp }}"
                        placeholder="Contoh: 081234567890"
                    >
                    <div class="helper-text">Masukkan nomor telepon aktif Anda</div>
                </div>

                <!-- Bio Singkat -->
                <div class="field-row">
                    <label class="field-label">Bio Singkat</label>
                    <textarea 
                        name="bio" 
                        class="input-field" 
                        rows="3"
                        placeholder="Ceritakan sedikit tentang diri Anda..."
                    >{{ $pembeli->bio ?? '' }}</textarea>
                    <div class="helper-text">Maksimal 200 karakter</div>
                </div>

                <!-- Alamat -->
                <div class="field-row">
                    <label class="field-label">Alamat</label>
                    <textarea 
                        name="alamat" 
                        class="input-field" 
                        rows="3"
                        placeholder="Masukkan alamat lengkap Anda..."
                    >{{ $pembeli->alamat ?? '' }}</textarea>
                    <div class="helper-text">Alamat lengkap untuk pengiriman</div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <a href="{{ route('pembeli.profil') }}" class="btn btn-outline">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const noHp = document.querySelector('input[name="no_hp"]').value;
            
            if (noHp && !/^[0-9]{10,13}$/.test(noHp)) {
                e.preventDefault();
                alert('Nomor telepon harus berisi 10-13 digit angka');
                return false;
            }

            // Disable button to prevent double submit
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        });

        // Auto-hide success message after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => successAlert.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>