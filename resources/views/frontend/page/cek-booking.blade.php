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

                {{-- Detail Booking --}}
                <div class="card shadow p-4">

                    <h4 class="mb-4">Detail Booking</h4>

                    {{-- Status --}}
                    <p><b>Status :</b></p>
                    @if($booking->status == 'pending')
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-hourglass-half me-2"></i>
                        <strong>Pending</strong> - Menunggu konfirmasi.
                    </div>
                    @elseif($booking->status == 'confirmed')
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Confirmed</strong> - Booking telah dikonfirmasi.
                    </div>
                    @else
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Cancelled</strong> - Booking telah dibatalkan.
                    </div>
                    @endif

                    {{-- Detail Booking Table --}}
                    <table class="table table-bordered mt-4">
                        <tr>
                            <th>Kode Booking</th>
                            <td>{{$booking->kode_booking}}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{$booking->nama}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{$booking->email}}</td>
                        </tr>
                        <tr>
                            <th>Check In</th>
                            <td>{{$booking->check_in}}</td>
                        </tr>
                        <tr>
                            <th>Check Out</th>
                            <td>{{$booking->check_out}}</td>
                        </tr>
                        <tr>
                            <th>Total Malam</th>
                            <td>{{$booking->total_malam}}</td>
                        </tr>
                        <tr>
                            <th>Total Harga</th>
                            <td>Rp {{number_format($booking->total_harga)}}</td>
                        </tr>
                    </table>

                    {{-- Bukti Pembayaran --}}
                    @if($booking->bukti_pembayaran)
                    <p class="mt-4"><b>Bukti Pembayaran :</b></p>
                    <img src="{{asset('storage/'.$booking->bukti_pembayaran)}}" width="200"
                        class="img-thumbnail border border-primary shadow-sm" alt="Bukti Pembayaran">
                    @endif

                    {{-- Form Upload Bukti Pembayaran --}}
                    @if($booking->status == 'pending' && !$booking->bukti_pembayaran)
                    <div class="mt-4">
                        <label class="mb-2 text-danger"><b>Belum ada Bukti Pembayaran</b></label>
                        <form method="POST" action="{{route('cek.booking.upload', $booking->id)}}"
                            enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <label class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti" class="form-control mb-3" required>
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-upload me-2"></i> Upload
                            </button>
                        </form>
                    </div>
                    @endif

                </div>

                @endif
            </div>
        </div>

    </div>
</section>

@endsection