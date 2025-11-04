<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Seniman</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .container { max-width: 800px; margin: 40px auto; padding: 24px; background:#fff; border-radius:12px; box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
        .field { margin-bottom: 16px; }
        label { display:block; font-size:14px; color:#666; margin-bottom:6px; }
        input[type="text"], textarea { width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; }
        textarea { min-height: 120px; resize: vertical; }
        .actions { display:flex; gap:12px; margin-top: 16px; }
        .btn { padding:10px 16px; border-radius:8px; text-decoration:none; display:inline-block; }
        .btn-primary { background:#111; color:#fff; }
        .btn-secondary { background:#f1f1f1; color:#111; }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <div class="logo">#</div>
            <div class="logo-text">JOGJA ARTSPHERE</div>
        </div>
        <div class="header-right">
            <a class="btn btn-secondary" href="{{ route('seniman.dashboard') }}">Kembali</a>
        </div>
    </header>

    <div class="container">
        <h2>Edit Profil Seniman</h2>

        @if ($errors->any())
            <div style="background:#ffecec;color:#b10000;padding:10px 12px;border-radius:8px;margin:10px 0;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seniman.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $seniman->nama) }}" required>
            </div>

            <div class="field">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio">{{ old('bio', $seniman->bio) }}</textarea>
            </div>

            <div class="field">
                <label for="foto">Foto (jpeg/png/jpg, maks 2MB)</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('seniman.dashboard') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>


