@extends('admin.layout')

@section('content')

<h3>Tambah Booking</h3>


<form action="{{route('admin.booking.store')}}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nama</label>
    <input name="nama" class="form-control mb-2" placeholder="Nama">

    <label>Email</label>
    <input name="email" class="form-control mb-2" placeholder="Email">

    <label>No Telp</label>
    <input name="no_telp" class="form-control mb-2" placeholder="No Telp">

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" class="form-control mb-2">
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select>

    <label>Sumber Booking</label>
    <select name="sumber" class="form-control">
        <option value="website">Website</option>
        <option value="booking.com">Booking.com</option>
        <option value="agoda">Agoda</option>
        <option value="tiket.com">Tiket.com</option>
        <option value="on_the_spot">On The Spot</option>
    </select>

    <label>Jumlah Tamu</label>
    <input name="jumlah_tamu" class="form-control mb-2" placeholder="Jumlah Tamu">

    <label>Jumlah Kamar</label>
    <input name="jumlah_kamar" class="form-control mb-2" placeholder="Jumlah Kamar">

    <div id="warning_tanggal" class="text-danger mb-2"></div>

    <label>Check In</label>
    <input type="date" name="check_in" class="form-control mb-2">

    <label>Check Out</label>
    <input type="date" name="check_out" class="form-control mb-2">

    <label>Total Malam</label>
    <input name="total_malam" class="form-control mb-2" placeholder="Total Malam" readonly>
    <label>Total Harga</label>
    <input name="total_harga" class="form-control mb-2" placeholder="Total Harga" readonly>

    <label>Request</label>
    <textarea name="catatan" class="form-control mb-2" placeholder="Request"></textarea>

    <label>Bukti Pembayaran</label>
    <input type="file" name="bukti_pembayaran" class="form-control mb-3">

    <button class="btn btn-primary">Tambah Booking</button>

</form>
<hr>
{{-- hitung harga --}}
<script>
    const hargaPerMalam = 350000;
    
    function hitungTotal() {
    
        let checkin = document.querySelector('[name=check_in]').value;
        let checkout = document.querySelector('[name=check_out]').value;
        let kamar = document.querySelector('[name=jumlah_kamar]').value;
    
        if(!checkin || !checkout || !kamar) return;
    
        let tgl1 = new Date(checkin);
        let tgl2 = new Date(checkout);
    
        let selisih = (tgl2 - tgl1) / (1000 * 60 * 60 * 24);
    
        if(selisih <= 0) return;
    
        document.querySelector('[name=total_malam]').value = selisih;
    
        let total = selisih * hargaPerMalam * kamar;
    
        document.querySelector('[name=total_harga]').value = total;
    }
    
    // trigger
    document.querySelector('[name=check_in]').addEventListener('change', hitungTotal);
    document.querySelector('[name=check_out]').addEventListener('change', hitungTotal);
    document.querySelector('[name=jumlah_kamar]').addEventListener('input', hitungTotal);
</script>
{{-- warning saat input kamar --}}
<script>
    let fullDates = [];
    
    fetch('/full-dates')
        .then(res => res.json())
        .then(data => {
            fullDates = data;
        });
    
    function cekTanggal() {
        let checkin = document.querySelector('[name=check_in]').value;
        let checkout = document.querySelector('[name=check_out]').value;
    
        let warning = document.getElementById('warning_tanggal');
    
        if(fullDates.includes(checkin) || fullDates.includes(checkout)){
            warning.innerHTML = "⚠️ Tanggal sudah penuh!";
        } else {
            warning.innerHTML = "";
        }
    }
    
    document.querySelector('[name=check_in]').addEventListener('change', cekTanggal);
    document.querySelector('[name=check_out]').addEventListener('change', cekTanggal);
</script>

@php
$color = 'success';

if($sisaKamar <= 1) $color='danger' ; elseif($sisaKamar <=3) $color='warning' ; @endphp <div
    class="alert alert-{{ $color }}">
    Sisa kamar hari ini: <b>{{ $sisaKamar }}</b>
    </div>

    <h3>Data Booking</h3>
    <form method="GET" class="mb-3 d-flex gap-2 flex-wrap">

        @php
        $current = request('sumber');
        @endphp

        <a href="{{ route('admin.bookings') }}" class="btn {{ !$current ? 'btn-primary' : 'btn-outline-primary' }}">
            Semua
        </a>

        <a href="?sumber=website" class="btn {{ $current == 'website' ? 'btn-primary' : 'btn-outline-primary' }}">
            Website
        </a>

        <a href="?sumber=booking.com"
            class="btn {{ $current == 'booking.com' ? 'btn-primary' : 'btn-outline-primary' }}">
            Booking.com
        </a>

        <a href="?sumber=agoda" class="btn {{ $current == 'agoda' ? 'btn-primary' : 'btn-outline-primary' }}">
            Agoda
        </a>

        <a href="?sumber=tiket.com" class="btn {{ $current == 'tiket.com' ? 'btn-primary' : 'btn-outline-primary' }}">
            Tiket.com
        </a>

        <a href="?sumber=on_the_spot"
            class="btn {{ $current == 'on_the_spot' ? 'btn-primary' : 'btn-outline-primary' }}">
            On The Spot
        </a>

    </form>

    <table class="table table-bordered">

        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Sumber</th>
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
            <td>
                @php
                $color = match($b->sumber) {
                'website' => 'primary',
                'booking.com' => 'success',
                'agoda' => 'warning',
                'tiket.com' => 'danger',
                'on_the_spot' => 'secondary',
                };
                @endphp

                <span class="badge bg-{{ $color }}">
                    {{ $b->sumber }}
                </span>
            </td>

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
                <img src="{{ asset('storage/'.$b->bukti_pembayaran) }}" width="80" class="img-thumbnail"
                    style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $b->id }}">
                @endif
                {{-- tampilin Modal --}}
                @if($b->bukti_pembayaran)
                <div class="modal fade" id="modalBukti{{ $b->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body text-center">
                                <img src="{{ asset('storage/'.$b->bukti_pembayaran) }}" class="img-fluid">
                            </div>

                        </div>
                    </div>
                </div>
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