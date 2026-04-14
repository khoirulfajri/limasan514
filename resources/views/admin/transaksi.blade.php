@extends('admin.layout')

@section('content')

<h3 class="mb-3">Input Transaksi</h3>

{{-- ======================
FORM TAMBAH TRANSAKSI
====================== --}}
<div class="card mb-4">
    <div class="card-body">

        <form action="{{route('admin.transaksi.store')}}" method="POST" enctype="multipart/form-data" class="row g-2">
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

            <div class="col-md-2">
                <input name="keterangan" class="form-control" placeholder="Keterangan">
            </div>
            <div class="col-md-2">
                <input type="file" name="bukti" class="form-control mb-2">
                {{-- <small class="text-muted">Opsional (nota, struk, dll)</small> --}}
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
                    <th width="120">Bukti</th>
                    <th width="120">Aksi</th>
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

                        @php
                        $bukti = null;

                        // jika dari booking
                        if($t->tipe == 'pemasukan' && $t->booking){
                        $bukti = $t->booking->bukti_pembayaran;
                        } else {
                        $bukti = $t->bukti;
                        }
                        @endphp

                        @if($bukti)

                        <img src="{{ asset('storage/'.$bukti) }}" width="60" style="cursor:pointer"
                            onclick="previewBukti('{{ asset('storage/'.$bukti) }}')">

                        <br>

                        <a href="{{ asset('storage/'.$bukti) }}" download class="btn btn-sm btn-outline-secondary mt-1">
                            Download
                        </a>

                        @else
                        <span class="text-muted">-</span>
                        @endif

                    </td>

                    <td class="text-center">

                        <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

                            {{-- AUTO (BOOKING) --}}
                            @if($t->booking_id)
                            {{-- <span class="badge bg-secondary px-3 py-2">
                                Auto
                            </span> --}}
                            @else
                            {{-- EDIT --}}
                            <button class="btn btn-sm btn-warning d-flex align-items-center gap-1 px-2"
                                onclick="openEditModal({{$t->id}}, '{{ $t->tanggal }}', '{{ $t->tipe }}', '{{ $t->jumlah }}', '{{ $t->keterangan }}')">
                                ✏️
                            </button>
                            @endif

                            {{-- DELETE --}}
                            <a href="{{route('admin.transaksi.delete',$t->id)}}"
                                class="btn btn-sm btn-danger d-flex align-items-center gap-1 px-2"
                                onclick="return confirm('Hapus transaksi ini?')">
                                🗑 
                            </a>

                        </div>

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

{{-- ==================
MODAL BUKTI
===================== --}}
<div class="modal fade" id="modalBukti" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Preview Bukti Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <img id="imgPreview" src="" class="img-fluid rounded">
            </div>

        </div>
    </div>
</div>

{{-- ======================
MODAL EDIT TRANSAKSI
====================== --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="formEdit" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="date" name="tanggal" id="edit_tanggal" class="form-control mb-2" required>

                    <select name="tipe" id="edit_tipe" class="form-control mb-2">
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>

                    <input name="jumlah" id="edit_jumlah" class="form-control mb-2" required>

                    <input name="keterangan" id="edit_keterangan" class="form-control mb-2">

                    <label>Ganti Bukti (opsional)</label>
                    <input type="file" name="bukti" class="form-control">

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>

            </form>

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
<script>
    function openEditModal(id, tanggal, tipe, jumlah, keterangan){
    
        document.getElementById('edit_tanggal').value = tanggal
        document.getElementById('edit_tipe').value = tipe
        document.getElementById('edit_jumlah').value = jumlah
        document.getElementById('edit_keterangan').value = keterangan
    
        document.getElementById('formEdit').action = `/admin/transaksi/update/${id}`
    
        let modal = new bootstrap.Modal(document.getElementById('modalEdit'))
        modal.show()
    }
</script>

@endsection