@extends('admin.layout')

@section('content')

<h3>Tambah Voucher</h3>

<form action="{{ route('admin.vouchers.store') }}" method="POST">
    @csrf

    <label>Kode Voucher</label>
    <input name="kode" class="form-control mb-2" placeholder="Contoh: DISKON10">

    <label>Tipe</label>
    <select name="tipe" class="form-control mb-2">
        <option value="persen">Persen</option>
        <option value="nominal">Nominal</option>
    </select>

    <label>Nilai</label>
    <input name="nilai" class="form-control mb-2" placeholder="Contoh: 10 atau 50000">

    <label>Minimal Transaksi</label>
    <input name="minimal_transaksi" class="form-control mb-2" placeholder="Opsional">

    <label>Kuota</label>
    <input name="kuota" class="form-control mb-2" placeholder="Opsional">

    <label>Expired</label>
    <input type="date" name="expired_at" class="form-control mb-2">

    <label>Status</label>
    <select name="is_active" class="form-control mb-3">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>

    <button class="btn btn-primary">Tambah Voucher</button>
</form>

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

        <td>{{ $v->expired_at ?? '-' }}</td>

        <td>
            <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </td>
    </tr>

    @endforeach

</table>

@endsection