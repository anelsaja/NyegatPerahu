<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nyegat Perahu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Montserrat', sans-serif;
        }
        .header-nyegat { background-color: #d8efff; padding: 15px; text-align: center; }
        .title-logo { font-weight: 900; font-size: 20px; color: #333; line-height: 1.2; }
        .title-logo span { color: #5bc0de; }

        .mobile-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header {
            background-color: #9ec3cf;
            text-align: center;
            padding: 20px 0;
            font-weight: bold;
            font-size: 24px;
            line-height: 1.2;
        }

        .header span {
            color: #2aa7df;
        }

        /* CONTENT DI TENGAH */
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            padding: 20px;
        }

        .title {
            font-size: 48px;
            font-weight: 900;
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-google {
            background-color: #8fd0e8;
            color: #000;
            font-weight: 600;
            border-radius: 15px;
            padding: 14px;
            font-size: 16px;
            border: none;
        }

        .btn-google img {
            width: 20px;
            margin-right: 10px;
        }

        /* FOOTER DI PALING BAWAH */
        .footer {
            position: absolute;
            bottom: 50px;
            width: 100%;
            padding: 0 20px;
            text-align: center;
        }

        .btn-daftar {
            background-color: #4f6f7a;
            color: white;
            border-radius: 15px;
            padding: 12px;
            width: 100%;
            border: none;
            font-weight: 500;
        }
    </style>
</head>

<body>

<div class="mobile-container">

    <!-- HEADER -->
    <div class="header-nyegat">
        <div class="title-logo">Nyegat<br><span>Perahu.</span></div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="title">Masuk</div>

        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <a href="{{ route('google.login') }}" class="btn btn-google d-flex align-items-center justify-content-center">
            <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png">
            Lanjutkan dengan Google
        </a>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p class="mb-2 font-weight-bold">Buat Akun Baru?</p>
        <a href="{{ route('google.login') }}" class="btn btn-daftar">Daftar</a>
    </div>

</div>

</body>
</html>