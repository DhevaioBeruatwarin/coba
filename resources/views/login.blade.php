<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        
        
        
        
        <div class="left-section">
            <div class="informasi">
                <h1>WELCOME!</h1>
            </div>
            <img src="{{ asset('assets/tulip(fix).png') }}" alt="" class="monikaCantik">
            <img src="{{ asset('assets/tulip(fix).png') }}" alt="" class="monikaCantik2">
            <img src="{{ asset('assets/tulip(fix).png') }}" alt="" class="monikaCantik3">
    </div>



    <div class="right-section">
        <h2>Login</h2>

        <form action="{{ '/login' }}" method="POST">
            @csrf
            
            <div class="formGroup">
                <label for="email">Email Address</label>
                <input type="email", id="email", name="email", placeholder="Enter your email here" required>
            </div>
        
        
            <div class="formGroup">
                <label for="password">Password</label>
                <input type="password", id="password", name="password", placeholder="Enter your password" required>
            </div>

            <div class="rememberMe">
                <input type="checkbox" alt="checkbox" name="checkbox" value="1">
                <label for="remember">Remember me</label>
            </div>

    
            <img class="ornament-top" src="{{ asset('assets/adajpg-removebg-preview.png') }}" alt="top decoration">
            <button type="submit" class="login">Login</button>
        </form>
    </div>

    <img src="{{ asset('assets/tengah.png') }}" alt="" class="divider-middle">

   </div #div penutup iki>
    
</body>
</html>