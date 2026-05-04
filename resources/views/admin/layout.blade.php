<!DOCTYPE html>
<html>

<head>

    <title>{{ $title ?? 'Admin' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar-desktop {
            width: 250px;
            background: #e09f9f;
            min-height: 100vh;
        }

        .sidebar-desktop a {
            color: white;
            display: block;
            padding: 12px;
            text-decoration: none;
        }

        .sidebar-desktop a:hover {
            background: #e7bbbb;
        }

        .offcanvas a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
        }

        .offcanvas a:hover {
            background: #f0f0f0;
        }
    </style>

</head>

<body>

    <!-- 🔥 NAVBAR (MOBILE) -->
    <nav class="navbar navbar-light bg-white shadow-sm d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-dark" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                <i class="fa fa-bars"></i>
            </button>
            <span class="fw-bold">Limasan 514</span>
        </div>
    </nav>

    <div class="d-flex">

        <!-- 🔥 SIDEBAR DESKTOP -->
        <div class="sidebar-desktop d-none d-md-block p-3">

            <h4 class="text-white text-center fw-bold">Limasan 514</h4>

            <a href="{{route('admin.dashboard')}}">Dashboard</a>
            <a href="{{route('admin.vouchers.index')}}">Voucher</a>
            <a href="{{route('admin.bookings')}}">Booking</a>
            <a href="{{route('admin.transaksi')}}">Transaksi</a>
            <a href="{{ route('admin.tamu') }}">Daftar Tamu</a>
            <a href="{{route('admin.laporan')}}">Laporan</a>

            <form action="{{route('admin.logout')}}" method="POST">
                @csrf
                <button class="btn btn-danger w-100 mt-3">Logout</button>
            </form>

        </div>

        <!-- 🔥 CONTENT -->
        <div class="flex-grow-1 p-3 p-md-4">

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

    <!-- 🔥 SIDEBAR MOBILE (OFFCANVAS) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile">

        <div class="offcanvas-header">
            <h5 class="fw-bold">Limasan 514</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">

            <a class="d-block mb-2" href="{{route('admin.dashboard')}}">Dashboard</a>
            <a class="d-block mb-2" href="{{route('admin.vouchers.index')}}">Voucher</a>
            <a class="d-block mb-2" href="{{route('admin.bookings')}}">Booking</a>
            <a class="d-block mb-2" href="{{route('admin.transaksi')}}">Transaksi</a>
            <a class="d-block mb-2" href="{{ route('admin.tamu') }}">Pengunjung</a>
            <a class="d-block mb-2" href="{{route('admin.laporan')}}">Laporan</a>

            <form action="{{route('admin.logout')}}" method="POST">
                @csrf
                <button class="btn btn-danger w-100 mt-3">Logout</button>
            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>