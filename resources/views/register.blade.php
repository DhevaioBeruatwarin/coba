<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Jogja Artsphere</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
  <div class="container">
    <!-- LEFT SECTION -->
    <div class="left-section">
      <div class="left-content">
        <h1>Create Your<br>Account</h1>
        <p>
          Join Jogja Artsphere to explore a marketplace full of original artworks and handmade creations. Connect with talented artists, support their craft, and be part of a growing creative network in Yogyakarta.
        </p>
      </div>
    </div>

    <!-- RIGHT SECTION -->
    <div class="right-section">
      <h2>Sign Up</h2>

      <img class="ornament-bottom" src="{{ asset('assets/w.png') }}" alt="bottom decoration">

      <!-- FORM REGISTRASI -->
      <form action="{{ url('/register') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="nama">Username</label>
          <input type="text" id="nama" name="nama" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="no_hp">Phone Number</label>
          <input type="tel" id="no_hp" name="no_hp" placeholder="Enter your phone number" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter password" required>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
        </div>

        <div class="form-group">
          <label for="role">Role</label>
          <select id="role" name="role" required>
            <option value="">Select role</option>
            <option value="seniman">Seniman</option>
            <option value="pembeli">Pembeli</option>
          </select>
        </div>

        <button type="submit" class="join">Join us</button>
      </form>

      <!-- Decorative top ornament -->
      <img class="ornament-top" src="{{ asset('assets/bunga.png') }}" alt="top decoration">
    </div>

    <!-- MIDDLE DIVIDER -->
    <img class="divider-middle" src="{{ asset('assets/tengah.png') }}" alt="ornamental divider">
  </div>
</body>
</html>
