<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem BBPK Jakarta</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        :root {
            --green-main: #009B4C;
            --green-light: #00B262;
            --green-dark: #007B39;
            --white: #ffffff;
            --gray: #f8f9fa;
            --shadow: rgba(0,0,0,0.15);
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--green-main), var(--green-light));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        body::before, body::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            z-index: 0;
        }
        body::before { width: 350px; height: 350px; top: -100px; left: -100px; }
        body::after { width: 500px; height: 500px; bottom: -150px; right: -150px; }

        .login-container {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 20px;
            width: 380px;
            box-shadow: 0 6px 25px var(--shadow);
            text-align: center;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.7s ease;
        }

        .login-container img { width: 80px; margin-bottom: 1rem; }
        h2 { margin-bottom: 1.2rem; color: var(--green-dark); font-weight: 600; }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: 0.85rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.2s;
        }

        input:focus {
            border-color: var(--green-main);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,155,76,0.15);
        }

        .btn-login {
            width: 100%;
            background-color: var(--green-main);
            color: var(--white);
            border: none;
            padding: 0.9rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 155, 76, 0.25);
        }

        .btn-login:hover { background-color: var(--green-dark); transform: translateY(-2px); }

        .links { margin-top: 1rem; font-size: 0.9rem; }
        .links a { color: var(--green-main); text-decoration: none; font-weight: 500; }
        .links a:hover { text-decoration: underline; }

        .footer { margin-top: 2rem; font-size: 0.8rem; color: #777; }

        @keyframes fadeIn { from {opacity: 0; transform: translateY(10px);} to {opacity: 1; transform: translateY(0);} }

        @media (max-width: 480px) { .login-container { width: 90%; padding: 2rem 1.5rem; } }
    </style>
</head>
<body>

<div class="login-container">
    <img src="{{ asset('images/logo-bbpk.png') }}" alt="Logo BBPK">
    <h2>Login Sistem BBPK Jakarta</h2>

    @if(session('error'))
        <div style="color:red; margin-bottom:10px;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="text" name="login" placeholder="Email atau NIP" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn-login">Masuk</button>
    </form>

    <div class="links">
        <a href="{{ route('password.request') }}">Lupa Password?</a><br>
        <a href="{{ route('register') }}">Belum punya akun? Daftar</a>
    </div>

    <div class="footer">© 2025 BBPK Jakarta</div>
</div>

</body>
</html>
