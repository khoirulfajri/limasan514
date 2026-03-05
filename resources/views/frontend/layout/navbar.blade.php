<div class="d-blox px-3 py-2 text-center text-bold text-white fst-italic" style="background-color: #e09f9f">
        Homestay Nuansa Jawa dengan Kenyamanan Modern
</div>
<nav class="navbar navbar-expand-lg navbar-light shadow" >
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Limasan 514</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end fw-bold" id="navbarNav" style="font-family: 'Raleway', sans-serif;">
            <div class="navbar-nav">
                <a class="nav-link active me-3" aria-current="page" href="#">Beranda</a>
                <a class="nav-link me-3" href="#">Fasilitas Kami</a>
                <a class="nav-link me-3" href="#">Booking</a>
                <a class="nav-link me-3" href="#">Hubungi Kami</a>
            </div>
            <!-- Kanan: Login/Register atau Profil -->
            <div class="d-flex align-items-center">
                @if(Cookie::has('ctoken'))
                <!-- Jika sudah login -->
                <div class="dropdown">
                    <a class="btn btn-white text-danger fw-bold dropdown-toggle" href="#" role="button"
                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i> {{ $user->nama ?? 'Nama Pengguna' }}
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="/profil">Lihat Profil</a></li>
                        <li><a class="dropdown-item" href="/logoutCustomer">Logout</a></li>
                    </ul>
                </div>
                @else
                <!-- Jika belum login -->
                <i class="fa-solid fa-user" style="color: hsl(0, 51%, 75%)"></i>
                <a class="btn fw-bold me-2" style="color: hsl(0, 51%, 75%)" href="/login" role="button">Masuk</a>
                @endif
            </div>
        </div>
    </div>
</nav>