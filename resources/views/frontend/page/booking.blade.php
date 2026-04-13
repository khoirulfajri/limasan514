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
                <div class="card card-booking p-4 shadow-lg rounded-4">
                    <h3 class="mb-4 fw-bold text-center text-danger py-3">Booking</h3>
                    <form method="POST" action="/booking/store" onsubmit="return validasiKamar()">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input class="form-control" name="nama" placeholder="Masukan Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="contoh@gmail.com"
                                required>
                            <small class="text-muted">* Email ini akan digunakan untuk mengirim invoice</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="tel" class="form-control" name="no_telp" placeholder="08xxxxxxx" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check In</label>
                            <input type="text" id="checkin" name="check_in" class="form-control"
                                placeholder="Tanggal Menginap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check Out</label>
                            <input type="text" id="checkout" name="check_out" class="form-control"
                                placeholder="Tanggal Selesai" required>
                        </div>
                        <div id="warning_kamar" class="text-danger"></div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Tamu</label>
                            <input type="number" class="form-control" name="jumlah_tamu" placeholder="Minimal 1 Orang"
                                min="1" max="10" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Kamar</label>
                            <input type="number" class="form-control" id="jumlah_kamar" name="jumlah_kamar"
                                placeholder="Minimal 1 Kamar" min="1" max="5" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" name="catatan" placeholder="Request"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Voucher</label>
                            <input type="text" class="form-control" name="kode_voucher"
                                placeholder="Masukkan kode voucher">
                        </div>
                        <div id="voucher_info" class="mt-2 small"></div>
                        <button class="btn btn-danger w-100 fw-bold py-2">
                            Booking Sekarang
                        </button>
                    </form>
                    <div class="mt-4 text-center">
                        <p class="fst-italic mb-1">Estimasi Harga</p>
                        <p class="fst-italic mb-1">Total Malam: <span id="total_malam" class="fw-bold">0</span></p>
                        <p class="mb-1">Diskon: <span id="diskon" class="fw-bold text-success">Rp 0</span></p>
                        <p class="h4 text-danger fw-bold fst-italic">Total Harga: <span id="total_harga">Rp 0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="harga_per_malam" value="{{ $harga }}">
</section>

<script>
    const hargaPerMalam = parseInt(document.getElementById("harga_per_malam").value) || 0;

    let lastAlert = false;

    // ======================
    // HITUNG HARGA
    // ======================
    async function hitungHarga(){

        let checkin = document.getElementById("checkin").value
        let checkout = document.getElementById("checkout").value
        let kamar = document.getElementById("jumlah_kamar").value
        let kode = document.querySelector("input[name=kode_voucher]").value
        let info = document.getElementById("voucher_info")

        if(checkin && checkout && kamar){

            let t1 = new Date(checkin)
            let t2 = new Date(checkout)

            let selisih = (t2 - t1) / (1000*60*60*24)

            if(selisih > 0){

                let total = selisih * hargaPerMalam * kamar

                let diskon = 0

                if(kode){
                    let res = await fetch(`/cek-voucher?kode=${kode}`)
                    let data = await res.json()

                    if(data.status){
                        if(data.tipe === 'persen'){
                            diskon = total * (data.nilai / 100)
                        } else {
                            diskon = data.nilai
                        }
                    }

                    if(data.status){
                        info.innerHTML = "✅ Voucher valid"
                        info.style.color = "green"
                    }else{
                        info.innerHTML = "❌ Voucher tidak valid"
                        info.style.color = "red"
                    }
                }

                diskon = Math.min(diskon, total)

                let totalAkhir = total - diskon

                document.getElementById("total_malam").innerHTML = selisih
                document.getElementById("diskon").innerHTML =
                    "Rp " + diskon.toLocaleString()
                document.getElementById("total_harga").innerHTML =
                    "Rp " + totalAkhir.toLocaleString()
            }
        }
    }

    // ======================
    // CEK KAMAR (ALERT)
    // ======================
    async function cekKamarAlert(){

        let checkin = document.getElementById("checkin").value
        let checkout = document.getElementById("checkout").value
        let kamar = document.getElementById("jumlah_kamar").value

        if(checkin && checkout){

            let res = await fetch(`/cek-kamar?checkin=${checkin}&checkout=${checkout}`)
            let data = await res.json()

            if(data.sisa < kamar){

                if(!lastAlert){
                    alert(`Kamar tidak cukup! Sisa hanya ${data.sisa}`)
                    lastAlert = true
                }

            } else {
                lastAlert = false
            }
        }
    }

    // ======================
    // VALIDASI SUBMIT
    // ======================
    async function validasiKamar(){

        let checkin = document.getElementById("checkin").value
        let checkout = document.getElementById("checkout").value
        let kamar = document.getElementById("jumlah_kamar").value

        let res = await fetch(`/cek-kamar?checkin=${checkin}&checkout=${checkout}`)
        let data = await res.json()

        if(data.sisa < kamar){
            alert(`Booking gagal! Sisa kamar hanya ${data.sisa}`)
            return false
        }

        return true
    }

    // ======================
    // EVENT
    // ======================
    let timeout;

    document.querySelector("input[name=kode_voucher]").addEventListener("input", () => {
        clearTimeout(timeout)
        timeout = setTimeout(hitungHarga, 500)
    })

    document.getElementById("checkin").addEventListener("change", () => {
        hitungHarga()
        cekKamarAlert()
    })

    document.getElementById("checkout").addEventListener("change", () => {
        hitungHarga()
        cekKamarAlert()
    })

    document.getElementById("jumlah_kamar").addEventListener("input", () => {
        hitungHarga()
        cekKamarAlert()
    })

    document.querySelector("input[name=kode_voucher]").addEventListener("input", hitungHarga)

</script>

@endsection