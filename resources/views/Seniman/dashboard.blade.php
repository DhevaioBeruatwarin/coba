<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard seniman</title>
</head>
<body>
       <!-- Navbar -->
    <header class="navbar">
        <div class="logo">JOGJA ARTSPHERE</div>
        <nav class="nav-links" style="display:flex;gap:12px;align-items:center;">
            @if(\Illuminate\Support\Facades\Auth::guard('seniman')->check())
                <a href="{{ route('seniman.edit.profil') }}" title="Profil" style="text-decoration:none;font-size:22px;">👤</a>
            @endif
        </nav>
    </header>
    
</body>
</html>