<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan BBPK Jakarta</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #0D1B2A;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background-color: #0D1B2A;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        header .left {
            display: flex;
            align-items: center;
        }

        header img {
            height: 45px;
            margin-right: 12px;
        }

        header h1 {
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        nav a {
            color: white;
            margin-left: 1.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }

        nav a:hover {
            color: #C19A6B;
        }

        .hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5rem 6rem;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1590608897129-79da98d1593d?auto=format&fit=crop&w=1500&q=80') center/cover no-repeat;
            opacity: 0.15;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .hero-text {
            max-width: 50%;
        }

        .hero-text h2 {
            font-size: 2.4rem;
            font-weight: 600;
            color: #0D1B2A;
            margin-bottom: 1rem;
        }

        .hero-text p {
            color: #333;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .btn-login {
            background-color: #C19A6B;
            color: white;
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #a97f54;
        }

        .hero img {
            width: 38%;
            border-radius: 12px;
            position: relative;
            z-index: 1;
        }

        footer {
            background-color: #0D1B2A;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 3rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="left">
            <img src="{{ asset('images/logo-bbpk.png') }}" alt="Logo BBPK Jakarta">
            <h1>Sistem Keuangan BBPK Jakarta</h1>
        </div>
        <nav>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h2>Transparansi dan Efisiensi dalam Setiap Pengelolaan Keuangan</h2>
                <p>
                    Aplikasi ini mendukung proses pengajuan, verifikasi, dan pelaporan keuangan di lingkungan BBPK Jakarta secara digital dan terintegrasi.
                </p>
                <a href="{{ route('login') }}">
                    <button class="btn-login">Masuk Sekarang</button>
                </a>
            </div>
            <img src="{{ asset('images/gedung-bbpk.png') }}">
        </div>
    </section>

    <footer>
        © 2025 BBPK Jakarta — Sistem Informasi Keuangan
    </footer>
</body>
</html>
