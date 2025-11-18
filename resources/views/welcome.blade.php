<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan BBPK Jakarta</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        /* === WARNA UTAMA KEMENKES === */
        :root {
            --green-main: #009B4C;
            --green-dark: #007B39;
            --green-light: #00B262;
            --gray-bg: #f8f9fa;
            --text-dark: #0D1B2A;
            --white: #ffffff;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--gray-bg);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1; /* Biar bagian tengah fleksibel, dorong footer ke bawah */
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background-color: var(--green-main);
            color: var(--white);
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
            font-weight: 600;
        }

        nav a {
            color: var(--white);
            margin-left: 1.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        nav a:hover {
            color: #d6f5e3;
        }

        .hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 3rem 6rem;
            overflow: hidden;
            min-height: 70vh; /* Biar tetap tinggi tapi gak bikin scroll */
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1590608897129-79da98d1593d?auto=format&fit=crop&w=1500&q=80') center/cover no-repeat;
            opacity: 0.12;
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
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--green-dark);
            margin-bottom: 1rem;
        }

        .hero-text p {
            color: #333;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .btn-login {
            background-color: var(--green-main);
            color: var(--white);
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 3px 6px rgba(0, 155, 76, 0.2);
        }

        .btn-login:hover {
            background-color: var(--green-dark);
            box-shadow: 0 4px 8px rgba(0, 123, 57, 0.3);
        }

        .hero img {
            width: 38%;
            border-radius: 12px;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        footer {
            background-color: var(--green-main);
            color: var(--white);
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
        }

        footer a {
            color: #d6f5e3;
            text-decoration: none;
            transition: 0.3s;
        }

        footer a:hover {
            color: var(--white);
        }

        @media (max-width: 992px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 2.5rem 2rem;
                min-height: auto;
            }

            .hero-text {
                max-width: 100%;
                margin-bottom: 2rem;
            }

            .hero img {
                width: 80%;
            }

            .hero-text h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="left">
            <img src="{{ asset('images/logo-bbpk.png') }}" alt="Logo BBPK Jakarta">
            <h1>Sistem Pengelolaan dan Pengadaan Barang BBPK Jakarta</h1>
        </div>
        <nav>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <div class="hero-text">
                    <h2>Efisiensi dan Akuntabilitas dalam Pengelolaan Aset dan Pengadaan Barang</h2>
                    <p>
                        Aplikasi ini mempermudah proses pengajuan kerusakan, permintaan pembelian, serta alur persetujuan antarbagian secara digital dan terintegrasi.
                    </p>
                    <a href="{{ route('login') }}">
                        <button class="btn-login">Masuk Sekarang</button>
                    </a>
                </div>
                <img src="{{ asset('images/gedung-bbpk.png') }}">
            </div>
        </section>
    </main>

    <footer>
        © 2025 BBPK Jakarta — Sistem Informasi Pengelolaan dan Pengadaan Barang
    </footer>
</body>
</html>
