@extends('frontend.layout.index')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-5">

        <div class="card p-4">

            <h4>Cek Booking</h4>

            <form method="POST" action="/cek-booking">

                @csrf

                <input class="form-control mb-3" name="kode_booking" placeholder="Kode Booking">

                <button class="btn btn-theme w-100">

                    Cek Booking

                </button>

            </form>

        </div>

    </div>

</div>

@endsection