@extends('frontend.layout.index')

@section('content')

<div class="row mt-5 justify-content-center">

    <div class="col-md-6 mt-5">
        <div class="card p-4">
            <h4 class="fw-bold text-center text-white py-2 bg-danger rounded">Detail Pembayaran</h4>
            <hr>
            <p>
                Kode Booking :
                <b>{{$booking['kode_booking']}}</b>
            </p>

            <p>
                Nama :
                {{$booking['nama']}}
            </p>
            <p>
                Email :
                {{$booking['email']}}
            </p>
            <p>
                Check In :
                {{$booking['check_in']}}
            </p>
            <p>
                Check Out :
                {{$booking['check_out']}}
            </p>
            <p>
                Total Malam :
                {{$booking['total_malam']}}
            </p>
            <p>
                Total Harga :
            <h3 class="fw-bold text-danger">Rp {{number_format($booking['total_harga'])}}</h3>
            </p>
            <p>
                Request :
                {{$booking['catatan'] ?? '-'}}
            </p>

            <hr>

            <h5>Metode Pembayaran</h5>

            <h4 class="fw-bold">BCA - 123 - 456 - 789</h4>
            <h5>an Limasan 514</h5>
            <div class="alert alert-warning">
                <p class="mb-0">Silahkan transfer sesuai dengan total harga diatas, lalu upload bukti pembayaran untuk
                    proses konfirmasi.</p>
            </div>
            <form method="POST" action="/booking/confirm" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="kode_booking" value="{{ $booking['kode_booking'] }}">

                <label>Upload Bukti Pembayaran</label>
                <input type="file" class="form-control mb-3" name="bukti" required>
                <span class="fs-6 fst-italic">(JPG, PNG max 25Mb)</span>

                <button class="btn btn-danger w-100">
                    Konfirmasi Booking
                </button>
            </form>
        </div>

    </div>

</div>

@endsection