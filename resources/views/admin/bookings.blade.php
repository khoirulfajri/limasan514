@extends('admin.layout')

@section('content')

@if (auth()->user()->role == 'admin')
<div class="d-flex justify-content-between mb-3">
    <h3>Data Booking</h3>

    <a href="{{route('admin.booking.form')}}" class="btn btn-primary">
        + Tambah Booking
    </a>
</div>
@endif

{{-- FILTER --}}
<div class="mb-3 d-flex gap-2 flex-wrap">

    @php $current = request('sumber'); @endphp

    <a href="{{ route('admin.bookings') }}" class="btn {{ !$current ? 'btn-primary' : 'btn-outline-primary' }}">
        Semua
    </a>

    @foreach(['website','booking.com','agoda','tiket.com','on_the_spot'] as $s)
    <a href="?sumber={{$s}}" class="btn {{ $current==$s ? 'btn-primary' : 'btn-outline-primary' }}">
        {{$s}}
    </a>
    @endforeach

</div>

{{-- WARNING --}}
@if($warning)
<div class="alert alert-warning">{{$warning}}</div>
@endif

<div class="card">
    <div class="card-body p-0">

        <table class="table table-bordered mb-0">

            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Sumber</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Bayar</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($bookings as $b)

                <tr>

                    <td>{{$b->kode_booking}}</td>
                    <td>{{$b->nama}}</td>

                    <td>
                        <span class="badge bg-info">{{$b->sumber}}</span>
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
                        <span class="badge bg-secondary">
                            {{ ucfirst($b->metode_pembayaran) }}
                        </span>
                    </td>

                    <td>
                        @if($b->status_pembayaran == 'lunas')
                        <span class="badge bg-success">Lunas</span>
                        @elseif($b->status_pembayaran == 'dp')
                        <span class="badge bg-warning">DP</span>
                        @else
                        <span class="badge bg-secondary">Menunggu</span>
                        @endif
                    </td>

                    <td>
                        @if($b->bukti_pembayaran)

                        <img src="{{ asset('storage/'.$b->bukti_pembayaran) }}" width="60" style="cursor:pointer"
                            onclick="previewBukti('{{ asset('storage/'.$b->bukti_pembayaran) }}')">

                        <br>

                        <a href="{{ asset('storage/'.$b->bukti_pembayaran) }}" download
                            class="btn btn-sm btn-outline-secondary mt-1">
                            Download
                        </a>

                        @endif
                    </td>

                    <td class="d-flex gap-1">
                        @if(auth()->user()->role == 'admin')

                        <a href="{{route('admin.booking.confirm',$b->id)}}" class="btn btn-success btn-sm">
                            ✔
                        </a>

                        <a href="{{route('admin.booking.form.edit',$b->id)}}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="{{route('admin.booking.delete',$b->id)}}" class="btn btn-danger btn-sm">
                            Cancel
                        </a>
                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>
</div>
{{-- modal Bukti Transfer --}}
<div class="modal fade" id="modalBukti" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Preview Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <img id="imgPreview" src="" class="img-fluid rounded">
            </div>

        </div>
    </div>
</div>
<script>
    function previewBukti(src) {
        document.getElementById('imgPreview').src = src;
    
        let modal = new bootstrap.Modal(document.getElementById('modalBukti'));
        modal.show();
    }
</script>
@endsection