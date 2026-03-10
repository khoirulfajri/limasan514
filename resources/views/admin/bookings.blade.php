@extends('admin.layout')

@section('content')

<h3>Tambah Booking</h3>

<form action="{{route('admin.booking.store')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <input name="nama" class="form-control mb-2" placeholder="Nama">

    <input name="email" class="form-control mb-2" placeholder="Email">

    <input name="no_telp" class="form-control mb-2" placeholder="No Telp">

    <select name="jenis_kelamin" class="form-control mb-2">
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select>

    <input name="jumlah_tamu" class="form-control mb-2" placeholder="Jumlah Tamu">

    <input name="jumlah_kamar" class="form-control mb-2" placeholder="Jumlah Kamar">

    <input type="date" name="check_in" class="form-control mb-2">

    <input type="date" name="check_out" class="form-control mb-2">

    <input name="total_malam" class="form-control mb-2" placeholder="Total Malam">

    <input name="total_harga" class="form-control mb-2" placeholder="Total Harga">

    <textarea name="catatan" class="form-control mb-2" placeholder="Catatan"></textarea>

    <label>Bukti Pembayaran</label>
    <input type="file" name="bukti_pembayaran" class="form-control mb-3">

    <button class="btn btn-primary">Tambah Booking</button>

</form>

<hr>

<h3>Data Booking</h3>

<table class="table table-bordered">

    <tr>
        <th>Kode</th>
        <th>Nama</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Status</th>
        <th>Total</th>
        <th>Bukti</th>
        <th>Aksi</th>
    </tr>

    @foreach($bookings as $b)

    <tr>

        <td>{{$b->kode_booking}}</td>

        <td>{{$b->nama}}</td>

        <td>{{$b->check_in}}</td>

        <td>{{$b->check_out}}</td>

        <td>

            @if($b->status=='pending')
            <span class="badge bg-warning">Pending</span>

            @elseif($b->status=='confirmed')
            <span class="badge bg-success">Confirmed</span>

            @else
            <span class="badge bg-danger">Cancelled</span>
            @endif

        </td>

        <td>Rp {{number_format($b->total_harga)}}</td>

        <td>

            @if($b->bukti_pembayaran)

            <img src="{{asset('storage/bukti/'.$b->bukti_pembayaran)}}" width="80">

            @endif

        </td>

        <td>

            <a href="{{route('admin.booking.confirm',$b->id)}}" class="btn btn-success btn-sm">
                Confirm
            </a>

            <a href="{{route('admin.booking.delete',$b->id)}}" class="btn btn-danger btn-sm">
                Hapus
            </a>

        </td>

    </tr>

    @endforeach

</table>

@endsection