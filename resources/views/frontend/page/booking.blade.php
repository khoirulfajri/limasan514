@extends('frontend.layout.index')
@section('content')
<section class="sectionBooking pt-5 bg-light" style="font-family: Rubik, sans-serif">
    <div class="row pt-5">
        <div class="d-flex justify-content-center flex-wrap">
            <!-- Bagian Informasi Guesthouse -->
            <div class="col-md-4 col-12 py-5 px-4 text-center">
                <img src="{{asset('assets/fotokamar1.jpeg')}}" class="img-fluid rounded mb-4 shadow"
                    alt="Guesthouse Limasan514">
                <h2 class="fw-bold text-danger">Guesthouse Limasan514</h2>
                <p class="fst-italic">Terdapat <b>5 kamar</b> dengan fasilitas lengkap dan nyaman untuk menginap.</p>
                <span class="h3 fw-bold text-danger">Rp 350.000 </span>
                <small class="fst-italic">/ malam</small>
                <hr class="my-4">
                <h5 class="fw-bold">Fasilitas:</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fa-solid fa-snowflake text-primary me-2"></i> AC
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-bed text-success me-2"></i> Kasur Queen
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-wifi text-warning me-2"></i> Wifi
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-shower text-info me-2"></i> Kamar mandi dalam
                    </li>
                </ul>
            </div>

            <!-- Bagian Form Booking -->
            <div class="col-md-5 col-12 py-5 px-4">
                <div class="card card-booking p-4 shadow">
                    <h3 class="mb-3 fw-bold text-danger text-center">Booking</h3>
                    <form method="POST" action="/booking/store">
                        @csrf
                        <label>Nama Lengkap</label>
                        <input class="form-control mb-3" name="nama" placeholder="Masukan Nama Lengkap" required>
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="contoh@gmail.com" required>
                        <small class="text-muted">* Email ini akan digunakan untuk mengirim invoice</small>
                        <div class="mb-3"></div>
                        <label>Nomor Telpon</label>
                        <input type="tel" class="form-control mb-3" name="no_telp" placeholder="08xxxxxxx" required>
                        <select class="form-control mb-3" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <label>Check In</label>
                        <input type="text" id="checkin" name="check_in" class="form-control mb-3"
                            placeholder="Tanggal Menginap" required>
                        <label>Check Out</label>
                        <input type="text" id="checkout" name="check_out" class="form-control mb-3"
                            placeholder="Tanggal Selesai" required>
                        <label>Jumlah Tamu</label>
                        <input type="number" class="form-control mb-3" name="jumlah_tamu" placeholder="Minimal 1 Orang"
                            min="1" max="10" value="1" required>
                        <label>Jumlah Kamar</label>
                        <input type="number" class="form-control mb-3" id="jumlah_kamar" name="jumlah_kamar"
                            placeholder="Minimal 1 Kamar" min="1" max="5" value="1" required>
                        <textarea class="form-control mb-3" name="catatan" placeholder="Request"></textarea>
                        <button class="btn btn-danger w-100">
                            Booking Sekarang
                        </button>
                    </form>
                    <p class="mt-4 fst-italic text-center">Estimasi Harga</p>
                    <p class="fst-italic text-center">Total Malam : <span id="total_malam"></span></p>
                    <p class="h2 text-danger fw-bold fst-italic text-center">Total Harga : <span id="total_harga">Rp
                            0</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- hitung harga --}}
<script>
    const hargaPerMalam = 350000;
    
    function hitungHarga(){
        let checkin = document.getElementById("checkin").value
        let checkout = document.getElementById("checkout").value
        let kamar = document.getElementById("jumlah_kamar").value
        
        if(checkin && checkout && kamar){
            let t1 = new Date(checkin)
            let t2 = new Date(checkout)
            let selisih = (t2 - t1) / (1000*60*60*24)
            
            if(selisih > 0){
                let total = selisih * hargaPerMalam * kamar
                document.getElementById("total_malam").innerHTML = selisih
                document.getElementById("total_harga").innerHTML =
                "Rp " + total.toLocaleString()
            }
        }
    }
    document.getElementById("checkin").addEventListener("change", hitungHarga)
    document.getElementById("checkout").addEventListener("change", hitungHarga) 
    document.getElementById("jumlah_kamar").addEventListener("input", hitungHarga)
</script>
@endsection