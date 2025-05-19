<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forbiden</title>

    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">Error 403!</h1>
            <p class="lead text-danger">
                Anda tidak memiliki Hak Akses untuk membuka halaman ini.
            </p>
            <a href="/" class="btn btn-warning fw-bold">Kembali</a>
        </div>
    </div>
</body>

</html>
