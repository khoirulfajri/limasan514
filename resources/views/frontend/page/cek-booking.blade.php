@extends('frontend.layout.index')

@section('content')

<section class="sectionCekbooking py-5">
    <div class="container py-5">

        <h2 class="text-center mb-4">Cek Status Booking</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">

                {{-- Form cek kode booking --}}
                <div class="card shadow p-4 mb-4">
                    <form method="POST" action="{{route('cek.booking.submit')}}">
                        @csrf

                        <label class="mb-2">Kode Booking / Email</label>
                        <input type="text" name="kode_booking" class="form-control mb-3"
                            placeholder="Masukkan kode booking atau email" required>

                        <button class="btn btn-danger w-100">Cek Booking</button>
                    </form>
                </div>

                @if(isset($booking))

                {{-- Detail booking --}}
                <div class="card shadow p-4">

                    <h4>Detail Booking</h4>

                    <p><b>Kode Booking :</b> {{$booking->kode_booking}}</p>
                    <p><b>Nama :</b> {{$booking->nama}}</p>
                    <p><b>Email :</b> {{$booking->email}}</p>
                    <p><b>Check In :</b> {{$booking->check_in}}</p>
                    <p><b>Check Out :</b> {{$booking->check_out}}</p>
                    <p><b>Total Malam :</b> {{$booking->total_malam}}</p>
                    <p><b>Total Harga :</b> Rp {{number_format($booking->total_harga)}}</p>
                    <p><b>Status :</b>
                        @if($booking->status=='pending')
                        <span class="badge bg-warning">Pending</span>
                        @elseif($booking->status=='confirmed')
                        <span class="badge bg-success">Confirmed</span>
                        @else
                        <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </p>

                    {{-- Bukti Pembayaran --}}
                    @if($booking->bukti_pembayaran)
                    <p><b>Bukti Pembayaran :</b></p>
                    <img src="{{asset('storage/bukti/'.$booking->bukti_pembayaran)}}" width="200" class="img-thumbnail">
                    @endif

                    {{-- Form Upload Bukti Pembayaran --}}
                    @if($booking->status=='pending' && !$booking->bukti_pembayaran)
                    <label class="mb-2">Belum ada Bukti Pembayaran</label>
                    <form method="POST" action="{{route('cek.booking.upload',$booking->id)}}"
                        enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <label>Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti" class="form-control mb-2" required>
                        <button class="btn btn-primary w-100">Upload</button>
                    </form>
                    @endif

                </div>

                @endif
            </div>
        </div>

    </div>
</section>

@endsection