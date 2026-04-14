@extends('admin.layout')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-3">

    <h3>{{ $booking ? 'Edit Booking' : 'Tambah Booking' }}</h3>

    <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
        ← Kembali
    </a>

</div>

<div class="card">
    <div class="card-body">

        <form action="{{ $booking ? route('admin.booking.update',$booking->id) : route('admin.booking.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf

            @if($booking)
            <input type="hidden" name="id" value="{{$booking->id}}">
            @endif

            <div class="row">

                {{-- ======================
                DATA CUSTOMER
                ====================== --}}
                <div class="col-md-6">

                    <h5>Data Customer</h5>
                    <hr>

                    <label class="form-label" >Nama</label>
                    <input name="nama" class="form-control mb-3" placeholder="Masukkan nama" value="{{ $booking->nama ?? '' }}" required>

                    <label class="form-label">Email</label>
                    <input name="email" class="form-control mb-3" placeholder="Masukkan email" value="{{ $booking->email ?? '' }}" required>

                    <label class="form-label">No. Telepon</label>
                    <input name="no_telp" class="form-control mb-3" placeholder="contoh: 089xxxxx" value="{{ $booking->no_telp ?? '' }}">

                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control mb-3">
                        <option value="L" {{ ($booking->jenis_kelamin ?? '')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ ($booking->jenis_kelamin ?? '')=='P'?'selected':'' }}>Perempuan</option>
                    </select>

                </div>

                {{-- ======================
                DETAIL BOOKING
                ====================== --}}
                <div class="col-md-6">

                    <h5>Detail Booking</h5>
                    <hr>

                    <label class="form-label">Sumber Booking</label>
                    <select name="sumber" class="form-control mb-3">
                        @foreach(['website','booking.com','agoda','tiket.com','on_the_spot'] as $s)
                        <option value="{{$s}}" {{ ($booking->sumber ?? '')==$s?'selected':'' }}>
                            {{$s}}
                        </option>
                        @endforeach
                    </select>

                    <label class="form-label">Jumlah Tamu</label>
                    <input type="number" name="jumlah_tamu" class="form-control mb-3"  placeholder="Masukkan jumlah tamu"
                        value="{{ $booking->jumlah_tamu ?? '' }}" min="1">

                    <label class="form-label" >Jumlah Kamar</label>
                    <input type="number" name="jumlah_kamar" class="form-control mb-3" placeholder="Masukkan jumlah kamar"
                        value="{{ $booking->jumlah_kamar ?? '' }}" min="1">

                    <label class="form-label">Kode Voucher</label>
                    <input name="kode_voucher" class="form-control mb-3" value="{{ $booking->voucher->kode ?? '' }}"
                        placeholder="Masukkan kode voucher">

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Check In</label>
                            <input type="date" name="check_in" class="form-control mb-3"
                                value="{{ $booking->check_in ?? '' }}" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Check Out</label>
                            <input type="date" name="check_out" class="form-control mb-3"
                                value="{{ $booking->check_out ?? '' }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Total Malam</label>
                            <input name="total_malam" class="form-control mb-3"
                                value="{{ $booking->total_malam ?? '' }}" readonly>
                        </div>
                        <div class="col">
                            <label class="form-label">Total Harga</label>
                            <input name="total_harga" class="form-control mb-3"
                                value="{{ $booking->total_harga ?? '' }}" readonly>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label class="form-label">Diskon</label>
                                <input id="diskon" class="form-control mb-3" value="{{ $booking->diskon ?? 0 }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ======================
            PEMBAYARAN
            ====================== --}}
            <div class="row mt-3">

                <div class="col-md-6">

                    <h5>Pembayaran</h5>
                    <hr>

                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control mb-3">
                        <option value="transfer" {{ ($booking->metode_pembayaran ?? '')=='transfer'?'selected':'' }}>
                            Transfer
                        </option>
                        <option value="cash" {{ ($booking->metode_pembayaran ?? '')=='cash'?'selected':'' }}>
                            Cash
                        </option>
                    </select>

                    <label class="form-label">Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-control mb-3">
                        <option value="menunggu_verifikasi" {{ ($booking->status_pembayaran ??
                            '')=='menunggu_verifikasi'?'selected':'' }}>
                            Menunggu Verifikasi
                        </option>
                        <option value="dp" {{ ($booking->status_pembayaran ?? '')=='dp'?'selected':'' }}>
                            DP
                        </option>
                        <option value="lunas" {{ ($booking->status_pembayaran ?? '')=='lunas'?'selected':'' }}>
                            Lunas
                        </option>
                    </select>

                </div>

                <div class="col-md-6">

                    <h5>Tambahan</h5>
                    <hr>

                    <label class="form-label">Request</label>
                    <textarea name="catatan" class="form-control mb-3">{{ $booking->catatan ?? '' }}</textarea>

                    <label class="form-label">Upload Bukti</label>

                    @if($booking && $booking->bukti_pembayaran)
                    <div class="mb-2">
                        <small class="text-muted">Bukti saat ini:</small><br>
                        <a href="{{ asset('storage/'.$booking->bukti_pembayaran) }}" target="_blank">
                            <img src="{{ asset('storage/'.$booking->bukti_pembayaran) }}" width="120"
                                class="img-thumbnail mt-1">
                        </a>
                    </div>
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah bukti</small>
                    @endif

                    <input type="file" name="bukti_pembayaran" class="form-control mb-3">
                    

                </div>

            </div>

            {{-- BUTTON --}}
            <div class="mt-3">
                <button class="btn btn-primary w-100">
                    {{ $booking ? 'Update Booking' : 'Tambah Booking' }}
                </button>
            </div>

        </form>
        <script>
            const isEdit = {{ $booking ? 'true' : 'false' }};
            const hargaPerMalam = {{ $harga ?? 0 }};
            
            async function hitungTotal(){
            
                let checkin = document.querySelector('[name=check_in]').value;
                let checkout = document.querySelector('[name=check_out]').value;
                let kamar = document.querySelector('[name=jumlah_kamar]').value;
                let kode = document.querySelector('[name=kode_voucher]').value;
            
                if (!checkin || !checkout || !kamar) return;
            
                let t1 = new Date(checkin);
                let t2 = new Date(checkout);
            
                let malam = (t2 - t1) / (1000 * 60 * 60 * 24);
            
                if (malam <= 0) return;
            
                document.querySelector('[name=total_malam]').value = malam;
            
                let total = malam * hargaPerMalam * kamar;
                let diskon = 0;
            
                // 🔥 CEK VOUCHER
                if(kode){
                    let res = await fetch(`/cek-voucher?kode=${kode}`);
                    let data = await res.json();
            
                    if(data.status){
                        if(data.tipe === 'persen'){
                            diskon = total * (data.nilai / 100);
                        } else {
                            diskon = data.nilai;
                        }
                    }
                }
            
                diskon = Math.min(diskon, total);
            
                let totalAkhir = total - diskon;
            
                document.querySelector('[name=total_harga]').value = totalAkhir;
                document.getElementById('diskon').value = diskon;
            }
            
            document.querySelector('[name=check_in]').addEventListener('change', hitungTotal);
            document.querySelector('[name=check_out]').addEventListener('change', hitungTotal);
            document.querySelector('[name=jumlah_kamar]').addEventListener('input', hitungTotal);
            document.querySelector('[name=kode_voucher]').addEventListener('input', hitungTotal);
        </script>
    </div>
</div>

@endsection