<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nyegat Perahu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .header-nyegat {
            background-color: #d8efff;
            padding: 15px;
            text-align: center;
        }

        .title-logo {
            font-weight: 900;
            font-size: 20px;
            color: black;
            line-height: 1.2;
        }
        
        .title-logo span {
            color: #5bc0de;
        }

        /* Desain Baru Navigasi Bawah */
        .bottom-nav { 
            width: 100%; 
            background-color: #3b769f;
            display: flex; 
            padding: 10px 0 5px 0; 
            position: fixed;
            bottom: 0;
        }

        .bottom-nav a { 
            color: white; 
            text-align: center; 
            font-size: 13px; 
            text-decoration: none; 
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 5px;
        }

        .bottom-nav a.active {
            color: white;
            font-weight: bold;
        }

        .bottom-nav a.active::after {
            content: '';
            display: block;
            width: 30px;
            height: 3px;
            background-color: white;
            margin-top: 5px;
            border-radius: 2px;
        }
        .nav-icon {
            display: block;
            font-size: 20px;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="header-nyegat">
        <div class="title-logo">Nyegat<span>Perahu.</span></div>
    </div>
        
    @yield('content')

    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-house-door-fill"></i></span>Beranda
        </a>
        <a href="{{ route('nelayan.index') }}" class="{{ request()->routeIs('nelayan.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-people-fill"></i></span>Nelayan
        </a>
        <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-receipt-cutoff"></i></span>Laporan
        </a>
        <a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-person-circle"></i></span>Profil
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>