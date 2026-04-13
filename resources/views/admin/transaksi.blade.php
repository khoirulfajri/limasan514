@extends('admin.layout')

@section('content')

<h3 class="mb-3">Input Transaksi</h3>

{{-- ======================
FORM TAMBAH TRANSAKSI
====================== --}}
<div class="card mb-4">
    <div class="card-body">

        <form action="{{route('admin.transaksi.store')}}" method="POST" class="row g-2">
            @csrf

            <div class="col-md-2">
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="col-md-2">
                <select name="tipe" class="form-control" required>
                    <option value="">Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            <div class="col-md-2">
                <input name="jumlah" class="form-control" placeholder="Jumlah" required>
            </div>

            <div class="col-md-4">
                <input name="keterangan" class="form-control" placeholder="Keterangan">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">

    <form method="GET" class="d-flex flex-wrap gap-2">

        {{-- FILTER TIPE --}}
        <select name="tipe" class="form-select w-auto">
            <option value="">Semua</option>
            <option value="pemasukan" {{request('tipe')=='pemasukan' ? 'selected' :''}}>Pemasukan</option>
            <option value="pengeluaran" {{request('tipe')=='pengeluaran' ? 'selected' :''}}>Pengeluaran</option>
        </select>

        {{-- TANGGAL DARI --}}
        <input type="date" name="dari" value="{{request('dari') ?? date('Y-m-d')}}" class="form-control w-auto">

        {{-- TANGGAL SAMPAI --}}
        <input type="date" name="sampai" value="{{request('sampai') ?? date('Y-m-d')}}" class="form-control w-auto">

        {{-- SEARCH --}}
        <div class="input-group w-auto">

            <span class="input-group-text">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>

            <input type="text" name="search" value="{{request('search')}}" class="form-control"
                placeholder="Cari kode / keterangan...">

        </div>

        <button class="btn btn-primary">
            Filter
        </button>

        {{-- RESET --}}
        @if(request()->hasAny(['search','tipe','dari','sampai']))
        <a href="{{route('admin.transaksi')}}" class="btn btn-outline-dark">
            Reset
        </a>
        @endif

    </form>

</div>

{{-- ======================
TABLE
====================== --}}
<div class="card">
    <div class="card-body p-0">

        <table class="table table-bordered mb-0">

            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $t)

                <tr>

                    <td>{{$t->kode_transaksi}}</td>

                    <td>{{date('d-m-Y', strtotime($t->tanggal))}}</td>

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

                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

{{-- ======================
PAGINATION
====================== --}}
<div class="mt-3">
    {{$data->appends(request()->query())->links()}}
</div>

@endsection