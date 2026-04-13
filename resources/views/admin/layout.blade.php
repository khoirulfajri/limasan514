<!DOCTYPE html>
<html>

<head>

    <title>{{ $title ?? 'Admin' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            background: #e09f9f;
            min-height: 100vh;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 12px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #e7bbbb;
        }
    </style>

</head>

<body>

    <div class="d-flex">

        <div class="sidebar p-3">

            <h4 class="text-white fw-bold text-center">Limasan 514</h4>

            <a href="{{route('admin.dashboard')}}">Dashboard</a>
            {{-- <a href="{{route('admin.users')}}">User</a> --}}
            <a href="{{route('admin.vouchers.index')}}">Voucher</a>
            <a href="{{route('admin.bookings')}}">Booking</a>
            <a href="{{route('admin.transaksi')}}">Transaksi</a>
            <a href="{{route('admin.laporan')}}">Laporan</a>

            <form action="{{route('admin.logout')}}" method="POST">
                @csrf
                <button class="btn btn-danger w-100 mt-3">Logout</button>
            </form>

        </div>

        <div class="flex-grow-1 p-4">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>