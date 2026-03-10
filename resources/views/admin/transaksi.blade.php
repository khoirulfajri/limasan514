@extends('admin.layout')

@section('content')

<h3>Data Transaksi</h3>

{{-- FORM TAMBAH TRANSAKSI --}}
<form action="{{route('admin.transaksi.store')}}" method="POST">
    @csrf

    <input type="date" name="tanggal" class="form-control mb-2" required>

    <select name="tipe" class="form-control mb-2" required>
        <option value="">-- Pilih Tipe Transaksi --</option>
        <option value="pemasukan">Pemasukan</option>
        <option value="pengeluaran">Pengeluaran</option>
    </select>

    <input name="jumlah" class="form-control mb-2" placeholder="Jumlah" required>

    <input name="keterangan" class="form-control mb-2" placeholder="Keterangan">

    <button class="btn btn-primary">
        Simpan Transaksi
    </button>

</form>

<hr>

{{-- SEARCH --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <form method="GET" class="d-flex">

        <div class="input-group">

            <span class="input-group-text">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>

            <input type="text" name="search" value="{{request('search')}}" class="form-control"
                placeholder="Cari kode / keterangan...">

            <button class="btn btn-secondary">
                Cari
            </button>

            @if(request('search'))
            <a href="{{route('admin.transaksi')}}" class="btn btn-outline-dark">
                Reset
            </a>
            @endif

        </div>

    </form>

</div>


<table class="table table-bordered">

    <tr>
        <th>Kode</th>
        <th>Tanggal</th>
        <th>Tipe</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>

    @foreach($data as $t)

    <tr>

        <td>{{$t->kode_transaksi}}</td>

        <td>{{$t->tanggal}}</td>

        <td>

            @if($t->tipe == 'pemasukan')
            <span class="badge bg-success">Pemasukan</span>
            @else
            <span class="badge bg-danger">Pengeluaran</span>
            @endif

        </td>

        <td>Rp {{number_format($t->jumlah)}}</td>

        <td>{{$t->keterangan}}</td>

        <td>

            <a href="{{route('admin.transaksi.delete',$t->id)}}" class="btn btn-danger btn-sm"
                onclick="return confirm('Hapus transaksi ini?')">
                Hapus
            </a>

        </td>

    </tr>

    @endforeach

</table>

{{-- PAGINATION --}}
{{$data->links()}}

<br>

<a href="{{route('admin.laporan.pdf')}}" class="btn btn-primary">
    Export PDF
</a>

@endsection