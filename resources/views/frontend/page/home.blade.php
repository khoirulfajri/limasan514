@extends('frontend.layout.index')

@section('content')
{{-- kembali ke atas --}}
<a href="#" class="btn btn-primary rounded-circle shadow-sm" id="back-to-top" role="button" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
</a>



<!-- JUMBOTRON -->
<section class="sectionJumbotron">
    <div class="container p-4 p-xl-5">
        <div class="row flex-lg-row-reverse align-items-center g-5">
            <div class="col-10 mx-auto col-sm-8 col-lg-6 ">
                <img class="d-block mx-auto img-fluid" alt="" loading="lazy" src="{{asset('assets/limasan.jpeg')}}">
            </div>
            <div class="col-lg-6">
                <div class="lc-block mb-3" style="color: #e09f9f">
                    <div editable="rich">
                        <h2 class="fw-bold display-5">Limasan 514</h2>
                        <h2 class="fw-bold display-5">
                            <span id="typewriter"></span>
                            <span class="typewriter_cursor"></span>
                        </h2>
                    </div>
                </div>

                <div class="lc-block mb-3 text-dark">
                    <div editable="rich">
                        <p class="lead">Pengen nginep di homestay yang nyaman dengan suasana Jawa yang kental?
                            Limasan 514 adalah pilihan tepat untukmu! Dengan desain tradisional yang memukau, fasilitas
                            modern, dan pelayanan ramah.
                        </p>
                    </div>
                </div>
                <div class="lc-block d-grid gap-2 d-md-flex justify-content-md-start">
                    <a class="btn px-4 me-md-2 text-white fw-bold" style="background-color: #e09f9f" href="/produk"
                        role="button">Booking Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- AKHIR JUMBOTRON -->

{{-- wave --}}
{{-- <div class="d-block mx-auto">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#e09f9f" fill-opacity="1"
            d="M0,128L40,160C80,192,160,256,240,272C320,288,400,256,480,213.3C560,171,640,117,720,101.3C800,85,880,107,960,128C1040,149,1120,171,1200,186.7C1280,203,1360,213,1400,218.7L1440,224L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
        </path>
    </svg>
</div> --}}
{{-- akhir wave --}}

<section class="sectionQuote py-3"
    style="background: linear-gradient(135deg, #e09f9f, #d87c7c); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);">
    <div class="container" style="padding: 3rem;">
        <div class="row">
            <div class="col-12 text-center text-white"
                style="font-family: 'Raleway', sans-serif; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);">
                <div class="quote-icon mb-4">
                    <i class="fas fa-quote-left fa-3x" ></i>
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

<section class="sectionFasilitas py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-dark">
                <h3 class="fw-bold ">Fasilitas Kami</h3>
                <p class="lead">Nikmati berbagai fasilitas lengkap yang kami tawarkan untuk kenyamananmu selama menginap
                    di Limasan 514.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section: tentang kami  -->
<section class="sectionTentangkami" style="padding-top: 2px; !important">
    <div class="container text-center text-md-start mt-2">
        <!-- Grid row -->
        <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto">
                <!-- Content -->
                <h6 class="text-uppercase fw-bold mb-4 ">
                    Tentang Kami
                </h6>
                <p>
                    <b>Limasan 514 </b>adalah penginapan bernuansa tradisional Jawa dengan kenyamanan modern.
                    Terletak di lingkungan yang tenang, Limasan 514 menghadirkan suasana hangat, alami,
                    dan cocok untuk keluarga maupun perjalanan santai. Dengan desain rumah limasan dan
                    fasilitas lengkap, kami siap memberikan pengalaman menginap yang nyaman dan penuh ketenangan.
                </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-3 mx-auto">
                <!-- Links -->
                <h6 class="text-uppercase fw-bold mb-4">
                    Ikuti Sosial Media Kami
                </h6>
                <p>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.instagram.com/muhkhoirulfajri/"
                        class="text-reset">
                        <i class="fab fa-instagram me-3"></i> @muhkhoirulfajri
                    </a>
                </p>
                <p>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/muhkhoirulfajri/"
                        class="text-reset">
                        <i class="fab fa-facebook me-3"></i> @muhkhoirulfajri
                    </a>
                </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-4 mx-auto">
                <!-- Links -->
                <h6 class="text-uppercase fw-bold mb-4">Hubungi Kami</h6>
                <h6 class="text-uppercase fw-bold mb-4">LIMASAN 514</h6>
                <p><a target="_blank" rel="noopener noreferrer" href="https://maps.app.goo.gl/RZH8jdtCUQZdZfA57"
                        class="text-reset">
                        <i class="fas fa-home me-3"></i> Gg. Bayu, Caturtunggal, Kab. Sleman 55281
                    </a>
                </p>
                <p>
                    <a target="_blank" rel="noopener noreferrer"
                        href="https://mail.google.com/mail/?view=cm&fs=1&to=ortindoprint@gmail.com" class="text-reset">
                        <i class="fab fa-google me-3"></i> limasan514@gmail.com
                    </a>
                </p>
                <p>
                    <a target="_blank" rel="noopener noreferrer"
                        href="https://api.whatsapp.com/send?phone=6289695100835&text=Halo%20Admin%20Saya%20Mau%20Order"
                        class="text-reset">
                        <i class="fab fa-whatsapp me-3"></i> 08xxxxx
                    </a>
                </p>
                <p>

                </p>
            </div>
            <!-- Grid column -->
            <!-- Grid row -->
        </div>
</section>
<!-- Section: tentang kami  -->



@endsection
@push('script')
<script>
    // Get the button element
let mybutton = document.getElementById("back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", function(e) {
    e.preventDefault(); // Prevent default anchor behavior
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Smooth scroll behavior
    });
});
</script>