<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Seniman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fafafa;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            background-color: #fff;
            height: 100vh;
            border-right: 1px solid #ddd;
            padding: 30px 20px;
        }
        .sidebar h5 {
            font-weight: 600;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        .sidebar ul li a.active {
            color: #7a5af5;
        }
        .main-content {
            padding: 40px 50px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin: 40px;
        }
        .profile-photo {
            text-align: center;
            margin-left: auto;
        }
        .profile-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn-upload {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #7a5af5;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-upload:hover {
            background-color: #6848e6;
        }
        .footer {
            background-color: #2c1e15;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }
        .footer h5 {
            color: #fff;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .footer p {
            font-size: 14px;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-3">
            <h5>My Shop</h5>
            <ul>
                <li><a href="{{ route('seniman.profil') }}" class="active">Profil</a></li>
                <li><a href="#">Karya Saya</a></li>

                <li><a href="{{ route('seniman.karya.upload') }}">Upload Karya</a></li>
                <li><a href="{{ route('seniman.edit.profil') }}">Edit Profil</a></li>
                <li><a href="{{ route('logout') }}">Keluar</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main-content flex-fill">
            <div class="d-flex justify-content-between align-items-start">
                <div class="col-md-8">
                    <h3 class="mb-4">Profil Saya</h3>
                    <div class="mb-3"><strong>Nama:</strong> {{ $seniman->nama }}</div>
                    <div class="mb-3"><strong>Email:</strong> {{ $seniman->email }}</div>
                    <div class="mb-3"><strong>No. Telepon:</strong> {{ $seniman->no_hp ?? '-' }}</div>
                    <div class="mb-3"><strong>Bidang Seni:</strong> {{ $seniman->bidang ?? 'Belum diisi' }}</div>
                    <div class="mb-3"><strong>Bio:</strong> {{ $seniman->bio ?? 'Belum ada bio' }}</div>
                    <div class="mb-3"><strong>Alamat:</strong> {{ $seniman->alamat ?? '-' }}</div>
                </div>

                <div class="profile-photo col-md-4">
                    @if($seniman->foto)
                        <img src="{{ asset('storage/foto_seniman/' . $seniman->foto) }}" alt="Foto Seniman">
                    @else
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Avatar Default">
                    @endif
                    <form action="# }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="file" name="foto" id="foto" hidden onchange="this.form.submit()">
                        <label for="foto" class="btn-upload">Pilih Gambar</label>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-3">
                    <h5>Jogja Artsphere</h5>
                    <p>Tentang Kami</p>
                    <p>Promo Hari Ini</p>
                    <p>Karya Asli Jogja</p>
                </div>
                <div class="col-md-3">
                    <h5>Bantuan</h5>
                    <p>Telepon: 0823-5314-0</p>
                    <p>Email: Customer.care@gmail.com</p>
                </div>
                <div class="col-md-3">
                    <h5>Metode Pembayaran</h5>
                    <p>BCA, BRI, Mandiri, Visa, Gopay, dll.</p>
                </div>
                <div class="col-md-3">
                    <h5>Ikuti Kami</h5>
                    <p>Instagram | Facebook | X</p>
                </div>
            </div>
            <hr style="background-color:#444;">
            <p class="mt-3">© 2025 Jogja Artsphere — Dukung Karya Lokal</p>
        </div>
    </footer>
</body>
</html>
