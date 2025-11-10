<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Karya - Jogja Artsphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Seniman/uploadKarya.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Upload Karya Seni</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('seniman.karya.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            @csrf

            <div class="mb-3">
                <label for="nama_karya" class="form-label">Nama Karya</label>
                <input type="text" name="nama_karya" id="nama_karya" class="form-control" placeholder="Masukkan nama karya" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" placeholder="Ceritakan tentang karya ini..." required></textarea>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" class="form-control" placeholder="Masukkan harga karya" required>
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label">Foto Karya</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
            </div>

            <div class="text-end">
                <a href="{{ route('seniman.profil') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
