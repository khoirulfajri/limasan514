@extends('frontend.layout.index')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-6">

        <div class="card p-4">

            <h4>Detail Pembayaran</h4>

            <hr>

            <p>
                Kode Booking :
                <b>{{$booking->kode_booking}}</b>
            </p>

            <p>
                Nama :
                {{$booking->nama}}
            </p>

            <p>
                Total Harga :
                <b>Rp {{number_format($booking->total_harga)}}</b>
            </p>

            <hr>

            <h5>Transfer ke</h5>

            <p>

                Bank BCA
                123456789
                a.n Limasan514

            </p>

            <form method="POST" action="/upload-bukti" enctype="multipart/form-data">

                @csrf

                <input type="hidden" name="kode_booking" value="{{$booking->kode_booking}}">

                <input type="file" class="form-control mb-3" name="bukti">

                <button class="btn btn-theme w-100">

                    Upload Bukti Pembayaran

                </button>

            </form>

        </div>

    </div>

</div>

@endsection