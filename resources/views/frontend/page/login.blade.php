<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Limasan 514</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Raleway -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f7f7f7, #fbecec);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Raleway', sans-serif;
        }

        /* tombol kembali */
        .back-home {
            position: absolute;
            top: 25px;
            left: 30px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: .3s;
        }

        .back-home:hover {
            color: #e09f9f;
        }

        /* card login */
        .auth-card {
            width: 420px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .nav-pills .nav-link.active {
            background: #e09f9f;
        }
    </style>

</head>

<body>

    <!-- Back to beranda -->
    <a href="/" class="back-home">
        ← Kembali ke Beranda
    </a>

    <div class="card auth-card">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h4 class="fw-bold">Limasan 514</h4>
                <p class="text-muted small">Silakan login atau daftar</p>
            </div>

            <ul class="nav nav-pills nav-justified mb-4">

                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#login">
                        Login
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#register">
                        Register
                    </button>
                </li>

            </ul>

            <div class="tab-content">

                <!-- LOGIN -->
                <div class="tab-pane fade show active" id="login">

                    <form method="POST" action="/loginCustomer">
                        @csrf

                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email@gmail.com">
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 Karakter">
                        </div>

                        <button class="btn btn-danger w-100">
                            Login
                        </button>

                    </form>

                </div>

                <!-- REGISTER -->
                <div class="tab-pane fade" id="register">

                    <form method="POST" action="/registerCustomer">
                        @csrf

                        <div class="mb-3">
                            <input type="text" name="nama" class="form-control" placeholder="Nama">
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>

                        <div class="mb-3">
                            <input type="text" name="telp" class="form-control" placeholder="No Telepon">
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>

                        <button class="btn btn-success w-100">
                            Register
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>