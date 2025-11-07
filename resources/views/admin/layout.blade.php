<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin BBPK Jakarta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ===================== GLOBAL ===================== */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FA;
            color: #0D1B2A;
            display: flex;
            font-size: 16px;
            line-height: 1.6;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #0D1B2A, #1B263B);
            color: white;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .sidebar h2 {
            color: #C19A6B;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: 0.3s;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #C19A6B;
            color: #0D1B2A;
        }

        /* ===================== CONTENT ===================== */
        .content {
            flex-grow: 1;
            padding: 2rem;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 600;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            font-size: 16px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        table th, table td {
            padding: 0.8rem 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #C19A6B;
            color: white;
            font-weight: 600;
        }

        /* Button */
        .btn {
            background-color: #C19A6B;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #a57f55;
        }

        /* Form */
        select, input, textarea {
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 1rem;
        }

        /* Alert */
        .alert {
            padding: 0.8rem 1rem;
            background-color: #DFF2BF;
            color: #4F8A10;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 16px;
        }

        /* Grid Statistik */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }

        .icon {
            font-size: 32px;
            background-color: #C19A6B;
            color: white;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .details h4 {
            margin: 0;
            font-size: 18px;
            color: #0D1B2A;
            font-weight: 600;
        }

        .details p {
            margin: 0.2rem 0 0 0;
            font-size: 20px;
            font-weight: 700;
            color: #C19A6B;
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="sidebar">
        <h2>Admin BBPK</h2>
        <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
        <a href="{{ route('admin.users') }}">👥 Manajemen Pengguna</a>
        <a href="{{ route('admin.kro.index') }}">🗂️ Manajemen KRO</a>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 2rem;">
            @csrf
            <button type="submit" class="btn" style="width:100%; text-align:left;">
                🚪 Logout
            </button>
        </form>
    </div>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>
