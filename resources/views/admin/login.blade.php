<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Limasan 514</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 340px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background-color: #d59797;
            border: none;
            color: white;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #c47f7f;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            color: #888;
        }
    </style>

</head>

<body>

    <div class="login-container">

        <img src="{{asset('assets/logoLimasan.png')}}" width="100">
        <h3 style="color:#c47f7f">Login Admin</h3>

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.login.process') }}" method="POST">

            @csrf

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>

        </form>

        <div class="footer">
            © 2025 Limasan 514
        </div>

    </div>

</body>

</html>