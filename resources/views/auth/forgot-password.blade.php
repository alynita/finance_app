<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem BBPK Jakarta</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --green-main: #009B4C;
            --green-light: #00B262;
            --green-dark: #007B39;
            --white: #ffffff;
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

        .card {
            background: var(--white);
            width: 400px;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 6px 25px var(--shadow);
            position: relative;
            z-index: 1;
            animation: fadeIn 0.7s ease;
        }

        h2 {
            text-align: center;
            color: var(--green-dark);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .desc {
            font-size: 14px;
            color: #444;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        input[type="email"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 5px;
            font-size: 14px;
        }

        input:focus {
            border-color: var(--green-main);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,155,76,0.15);
        }

        .btn {
            width: 100%;
            background-color: var(--green-main);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1.3rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 155, 76, 0.25);
        }

        .btn:hover { background-color: var(--green-dark); transform: translateY(-2px); }

        .status {
            background: #d7f5e5;
            color: #007a37;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
            border: 1px solid #b9e8ce;
        }

        .error {
            color: red;
            font-size: 13px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="card">

    <h2>Lupa Password</h2>

    <div class="desc">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    @if (session('status'))
        <div class="status">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
        >

        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn">
            Kirim Link Reset Password
        </button>
    </form>

</div>

</body>
</html>
