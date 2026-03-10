@extends('frontend.layout.index')

@section('content')
{{-- kembali ke atas --}}
<button id="backToTop" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- JUMBOTRON -->
<div class="pb-5"></div>
<section class="sectionJumbotron pt-5">
    <div class="container p-4 p-xl-5">
        <div class="row flex-lg-row-reverse align-items-center g-5">
            <div class="col-10 mx-auto col-sm-8 col-lg-6">
                <img class="d-block mx-auto img-fluid animated bounce up-down" alt="" loading="lazy"
                    src="{{asset('assets/img-jumbotron.png')}}">
            </div>
            <div class="col-lg-6">
                <div class="lc-block mb-3">
                    <div editable="rich">
                        <h2 class="text-danger fw-bold display-5 typing-effect" id="typing-text"></h2>
                    </div>
                </div>

                <div class="lc-block mb-3 text-dark" style="font-family: raleway, sans-serif;">
                    <div editable="rich">
                        <p class="lead">Pengen nginep di Guesthouse yang nyaman dengan suasana Jawa yang kental?
                            Limasan 514 adalah pilihan tepat untukmu! Dengan desain tradisional yang memukau, fasilitas
                            modern, dan pelayanan ramah.
                        </p>
                    </div>
                </div>
                <div class="lc-block d-grid gap-2 d-md-flex justify-content-md-start">
                    <a class="btn btn-danger px-4 me-md-2 text-white fw-bold" href="/booking" role="button">Booking
                        Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- AKHIR JUMBOTRON -->

{{-- section untuk Quote --}}
<section class="sectionQuote py-3"
    style="background: linear-gradient(135deg, #e09f9f, #d87c7c); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);">
    <div class="container" style="padding: 3rem;">
        <div class="row">
            <div class="col-12 text-center text-white"
                style="font-family: 'Raleway', sans-serif; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);">
                <div class="quote-icon mb-4">
                    <i class="fas fa-quote-left fa-3x"></i>
                </div>
                <h1 class="fw-bold fst-italic" style="font-size: 2.5rem; line-height: 1.3; margin-top: 1rem;">Pengalaman
                    Menginap Tak Terlupakan!</h1>
                <p class="lead mt-3" style="font-size: 1.2rem; font-style: italic; margin-top: 1.5rem;">Limasan 514
                    adalah pilihan tepat untukmu! Dengan desain tradisional yang memukau,
                    fasilitas modern, dan pelayanan ramah.</p>
            </div>
        </div>
    </div>
</section>
{{-- akhir Quote --}}
{{-- section untuk Fasilitas --}}
<section class="sectionFasilitas py-5" id="fasilitas">
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }
    </style>

    <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center text-dark" style="font-family: Raleway, sans-serif">
                <h3 class="fw-bold display-6">Fasilitas Kami</h3>
                <p class="lead">Nikmati berbagai fasilitas lengkap yang kami tawarkan untuk kenyamananmu selama menginap
                    di Limasan 514.</p>
            </div>
        </div>
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow border-0 h-100">
                    <img src="{{ asset('assets/fotokamar1.jpeg') }}" class="card-img-top" alt="Fasilitas 1">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Kamar Nyaman</h5>
                        <p class="card-text">Kamar dengan fasilitas lengkap untuk kenyamanan istirahat Anda.</p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow border-0 h-100">
                    <img src="{{ asset('assets/dapur.jpeg') }}" class="card-img-top" alt="Fasilitas 2">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Dapur</h5>
                        <p class="card-text">Ruangan Dapur yang bersih dan lengkap.</p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow border-0 h-100">
                    <img src="{{ asset('assets/fasilitas4.jpeg') }}" class="card-img-top" alt="Fasilitas 3">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Ruang Santai</h5>
                        <p class="card-text">Ruang santai yang nyaman untuk bersantai bersama keluarga.</p>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow border-0 h-100">
                    <img src="{{ asset('assets/fasilitas5.jpeg') }}" class="card-img-top" alt="Fasilitas 4">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Parkiran Luas</h5>
                        <p class="card-text">Memiliki tempat parkir yang luas.</p>
                    </div>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow border-0 h-100">
                    <img src="{{ asset('assets/kamarmandi.jpeg') }}" class="card-img-top" alt="Fasilitas 5">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Kamar Mandi</h5>
                        <p class="card-text">Kamar mandi bersih dan memiliki air panas dingin.</p>
                    </div>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow border-0 h-100">
                    <img src="{{ asset('assets/fotokamar3.jpeg') }}" class="card-img-top" alt="Fasilitas 5">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Kamar Nyaman</h5>
                        <p class="card-text">Kamar dengan fasilitas lengkap untuk kenyamanan istirahat Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Akhir Section Fasilitas --}}

<section class="hubungi-kami py-5 bg-white" id="hubungiKami">
    <div class="container py-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Hubungi Kami</h2>
            <p class="text-muted">Kami siap membantu kebutuhan menginap Anda di Limasan 514</p>
        </div>

        <div class="row gy-4">

            <!-- Tentang Kami -->
            <div class="col-lg-4">
                <h5 class="footer-title">Tentang Kami</h5>
                <p>
                    <b>Limasan 514</b> adalah penginapan bernuansa tradisional Jawa dengan kenyamanan modern.
                    Terletak di lingkungan yang tenang dan cocok untuk keluarga maupun perjalanan santai.
                    Kami menghadirkan suasana hangat dan alami untuk pengalaman menginap yang nyaman.
                </p>
            </div>

            <!-- Sosial Media -->
            <div class="col-lg-3">
                <h5 class="footer-title">Ikuti Kami</h5>

                <p>
                    <a href="https://instagram.com/limasan514" target="_blank" class="footer-link">
                        <i class="fab fa-instagram me-2"></i> Instagram
                    </a>
                </p>
                {{-- <p>
                    <a href="https://facebook.com" target="_blank" class="footer-link">
                        <i class="fab fa-facebook me-2"></i> Facebook
                    </a>
                </p>
                <p>
                    <a href="https://tiktok.com" target="_blank" class="footer-link">
                        <i class="fab fa-tiktok me-2"></i> TikTok
                    </a>
                </p> --}}
            </div>

            <!-- Kontak -->
            <div class="col-lg-5">
                <h5 class="footer-title">Kontak</h5>
                <p>
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    Gg. Batik Gringsing Jl. Prawirotaman No.514, Brontokusuman, Kec. Mergangsan, Kota Yogyakarta, Daerah
                    Istimewa Yogyakarta 55153
                </p>
                <p>
                    <a href="mailto:limasan514@gmail.com" class="footer-link">
                        <i class="fas fa-envelope me-2"></i>
                        limasan514@gmail.com
                    </a>
                </p>
                <p>
                    <a href="https://wa.me/682134916989" target="_blank" class="footer-link">
                        <i class="fab fa-whatsapp me-2 text-success"></i>
                        0821 3491 6989
                    </a>
                </p>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="map-container">

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7318470243513!2d110.3700126!3d-7.8181828!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5754c9960e61%3A0x96fc2b8aed9744a8!2sLimasan514!5e0!3m2!1sid!2sid!4v1772983590650!5m2!1sid!2sid"
                        width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const texts = ["Limasan 514", "Guesthouse Ternyaman di Jogja"]; // Teks yang akan diketik
        const typingElement = document.getElementById("typing-text");
        let textIndex = 0; // Indeks teks saat ini
        let charIndex = 0; // Indeks karakter saat ini

        function typeText() {
            if (charIndex < texts[textIndex].length) {
                typingElement.textContent += texts[textIndex][charIndex];
                charIndex++;
                setTimeout(typeText, 300); // Kecepatan mengetik (ms)
            } else {
                // Setelah selesai mengetik satu teks, tunggu sebentar lalu ketik teks berikutnya
                setTimeout(() => {
                    charIndex = 0;
                    textIndex++;
                    if (textIndex >= texts.length) {
                        textIndex = 0; // Reset ke teks pertama untuk efek infinite
                    }
                    typingElement.textContent = ""; // Hapus teks sebelumnya
                    typeText();
                }, 3000); // Tunggu 1 detik sebelum mengetik teks berikutnya
            }
        }

        typeText();
    });
</script>
{{-- navbar Menu Aktif --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
    
        const sections = document.querySelectorAll("section");
        const navLinks = document.querySelectorAll(".nav-menu");
    
        function changeActiveMenu() {
            let scrollPosition = window.scrollY + 120;
    
            sections.forEach(section => {
                if (
                    scrollPosition >= section.offsetTop &&
                    scrollPosition < section.offsetTop + section.offsetHeight
                ) {
                    navLinks.forEach(link => {
                        link.classList.remove("active");
    
                        if(link.getAttribute("href") === "#" + section.id){
                            link.classList.add("active");
                        }
                    });
                }
            });
        }
    
        window.addEventListener("scroll", changeActiveMenu);
    
    });
</script>