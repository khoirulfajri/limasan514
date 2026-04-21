@extends('admin.layout')

@section('content')

@if (auth()->user()->role == 'admin')
<h3>Tambah Voucher</h3>
<div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah Voucher
    </button>
</div>
@endif

<hr>
<h3>Data Voucher</h3>

<table class="table table-bordered">

    <tr>
        <th>Kode</th>
        <th>Tipe</th>
        <th>Nilai</th>
        <th>Minimal</th>
        <th>Kuota</th>
        <th>Dipakai</th>
        <th>Status</th>
        <th>Expired</th>
        <th>Aksi</th>
    </tr>

    @foreach($vouchers as $v)

    <tr>
        <td><b>{{ $v->kode }}</b></td>

        <td>
            @if($v->tipe == 'persen')
            <span class="badge bg-info">%</span>
            @else
            <span class="badge bg-warning">Rp</span>
            @endif
        </td>

        <td>
            @if($v->tipe == 'persen')
            {{ $v->nilai }}%
            @else
            Rp {{ number_format($v->nilai) }}
            @endif
        </td>

        <td>
            {{ $v->minimal_transaksi ? 'Rp '.number_format($v->minimal_transaksi) : '-' }}
        </td>

        <td>{{ $v->kuota ?? '∞' }}</td>

        <td>{{ $v->digunakan }}</td>

        <td>
            @if($v->is_active)
            <span class="badge bg-success">Aktif</span>
            @else
            <span class="badge bg-danger">Nonaktif</span>
            @endif
        </td>

        <td>{{ $v->expired_at ?? '-' }}
            @if($v->expired_at && now()->gt($v->expired_at))
            <span class="badge bg-dark">Expired</span>
            @endif
        </td>

        <td>

            <div class="d-flex gap-1">
                @if(auth()->user()->role == 'admin')

                <button class="btn btn-warning btn-sm"
                    onclick="openEditVoucher({{ $v->id }}, '{{ $v->kode }}', '{{ $v->tipe }}', '{{ $v->nilai }}', '{{ $v->minimal_transaksi }}', '{{ $v->kuota }}', '{{ $v->expired_at }}', '{{ $v->is_active }}')">
                    ✏️
                </button>

                <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm">🗑</button>
                </form>
                @endif

            </div>

        </td>
    </tr>

    @endforeach

</table>

{{-- =======================
modal Tambah
======================== --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input name="kode" class="form-control mb-2" placeholder="Kode Voucher">

                    <select name="tipe" class="form-control mb-2">
                        <option value="persen">Persen</option>
                        <option value="nominal">Nominal</option>
                    </select>

                    <input name="nilai" class="form-control mb-2" placeholder="Nilai">

                    <input name="minimal_transaksi" class="form-control mb-2" placeholder="Minimal Transaksi">

                    <input name="kuota" class="form-control mb-2" placeholder="Kuota">

                    <input type="date" name="expired_at" placeholder="Expired" class="form-control mb-2">

                    <select name="is_active" class="form-control">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ======
modal Edit
============ --}}
<div class="modal fade" id="modalEditVoucher">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="formEditVoucher">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Kode Voucher</label>
                    <input name="kode" id="edit_kode" class="form-control mb-2">

                    <label>Tipe</label>
                    <select name="tipe" id="edit_tipe" class="form-control mb-2">
                        <option value="persen">Persen</option>
                        <option value="nominal">Nominal</option>
                    </select>

                    <label>Nilai</label>
                    <input name="nilai" id="edit_nilai" class="form-control mb-2">

                    <label>Minimal Transaksi</label>
                    <input name="minimal_transaksi" id="edit_minimal" class="form-control mb-2">

                    <label>Kuota</label>
                    <input name="kuota" id="edit_kuota" class="form-control mb-2">

                    <label>Expired</label>
                    <input type="date" name="expired_at" id="edit_expired" class="form-control mb-2">

                    <label>Status</label>
                    <select name="is_active" id="edit_status" class="form-control">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
    function openEditVoucher(id, kode, tipe, nilai, minimal, kuota, expired, status){
    
        document.getElementById('edit_kode').value = kode
        document.getElementById('edit_tipe').value = tipe
        document.getElementById('edit_nilai').value = nilai
        document.getElementById('edit_minimal').value = minimal ?? ''
        document.getElementById('edit_kuota').value = kuota ?? ''
        document.getElementById('edit_expired').value = expired ?? ''
        document.getElementById('edit_status').value = status
    
        document.getElementById('formEditVoucher').action = `/admin/vouchers/${id}`
    
        let modal = new bootstrap.Modal(document.getElementById('modalEditVoucher'))
        modal.show()
    }
</script>

@endsection