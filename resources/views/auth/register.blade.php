<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Pengajuan Barang BBPK Jakarta</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #E8F3F1, #C8E6E2);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.15);
            text-align: center;
        }

        .register-container img {
            width: 75px;
            margin-bottom: 1rem;
        }

        h2 {
            margin-bottom: 1.2rem;
            color: #004D47;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[name="nip"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.2s;
        }

        input:focus {
            border-color: #006C67;
            outline: none;
            box-shadow: 0 0 5px rgba(0,108,103,0.3);
        }

        .btn-register {
            width: 100%;
            background-color: #006C67;
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-register:hover {
            background-color: #004D47;
        }

        .links {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .links a {
            color: #006C67;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <img src="{{ asset('images/logo-bbpk.png') }}" alt="Logo BBPK">
        <h2>Daftar Akun Pengajuan Barang</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" required autofocus>
            <input type="email" name="email" placeholder="Email" required>
            <input type="nip" name="nip" placeholder="NIP" required>
            <input type="password" name="password" placeholder="Password" required>
            <small style="display:block; text-align:left; color:#555; margin-top:-0.5rem; margin-bottom:1rem; font-size:0.85rem;">
                Password minimal 8 karakter, mengandung huruf kecil dan angka.
            </small>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
            <button type="submit" class="btn-register">Daftar</button>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">Sudah punya akun? Masuk</a>
        </div>

        <div class="footer">
            © 2025 BBPK Jakarta
        </div>
    </div>

</body>
</html>
