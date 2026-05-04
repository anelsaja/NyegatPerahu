<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nyegat Perahu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .header-nyegat { background-color: #d8efff; padding: 15px; text-align: center; }
        .title-logo { font-weight: 900; font-size: 20px; color: #333; line-height: 1.2; }
        .title-logo span { color: #5bc0de; }

        /* Desain Baru Navigasi Bawah */
        .bottom-nav { 
            width: 100%; 
            background-color: #3b769f; /* Warna biru sesuai desain */
            display: flex; 
            justify-content: space-around; 
            padding: 10px 0 5px 0; 
            z-index: 1030;
            position: fixed;
            bottom: 0;
            margin-top: auto; 
        }

        .bottom-nav a { 
            color: #b3d4ec; 
            text-align: center; 
            font-size: 12px; 
            text-decoration: none; 
            transition: color 0.3s ease; 
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 5px;
        }

        .bottom-nav a.active { color: #ffffff; font-weight: bold; }
        .bottom-nav a.active::after {
            content: '';
            display: block;
            width: 30px;
            height: 3px;
            background-color: #ffffff;
            margin-top: 5px;
            border-radius: 2px;
        }
        .nav-icon { display: block; font-size: 20px; margin-bottom: 3px; }
    </style>
</head>
<body>
    <div class="header-nyegat">
        <div class="title-logo">Nyegat<span>Perahu.</span></div>
    </div>

    <div class="mobile-container">
        
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
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>