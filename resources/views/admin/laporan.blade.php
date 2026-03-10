@extends('admin.layout')

@section('content')

<h4 class="mb-4 fw-bold">Laporan Keuangan</h4>

<a href="{{route('admin.laporan.export')}}" class="btn btn-outline-danger mb-3">
    <i class="fa fa-file-pdf"></i> Export PDF
</a>


<div class="row mb-4">

    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Pemasukan</h6>
                <h4>Rp {{number_format($pemasukan)}}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Total Pengeluaran</h6>
                <h4>Rp {{number_format($pengeluaran)}}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Saldo</h6>
                <h4>Rp {{number_format($saldo)}}</h4>
            </div>
        </div>
    </div>

</div>


<div class="card p-4 mb-4">

    <h5>Grafik Keuangan Tahun {{date('Y')}}</h5>

    <canvas id="chartKeuangan"></canvas>

</div>


<div class="card p-4 mb-4">

    <h5>Laporan Laba Rugi</h5>

    <table class="table table-bordered mt-3">

        <tr>
            <th>Total Pemasukan</th>
            <td class="text-end">Rp {{number_format($pemasukan)}}</td>
        </tr>

        <tr>
            <th>Total Pengeluaran</th>
            <td class="text-end">Rp {{number_format($pengeluaran)}}</td>
        </tr>

        <tr>
            <th>Laba Bersih</th>
            <td class="text-end fw-bold">Rp {{number_format($saldo)}}</td>
        </tr>

        <tr>
            <th>Status</th>

            <td class="text-end">

                @if($saldo >= 0)

                <span class="badge bg-success">LABA</span>

                @else

                <span class="badge bg-danger">RUGI</span>

                @endif

            </td>

        </tr>

    </table>

</div>


<div class="card p-4 mb-4">

    <h5>Data Transaksi</h5>

    <table class="table table-bordered table-striped mt-3">

        <thead>

            <tr>

                <th>Kode</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Keterangan</th>

            </tr>

        </thead>

        <tbody>

            @foreach($data as $t)

            <tr>

                <td>{{$t->kode_transaksi}}</td>

                <td>{{$t->tanggal}}</td>

                <td>

                    @if($t->tipe=='pemasukan')

                    <span class="badge bg-success">Pemasukan</span>

                    @else

                    <span class="badge bg-danger">Pengeluaran</span>

                    @endif

                </td>

                <td>Rp {{number_format($t->jumlah)}}</td>

                <td>{{$t->keterangan}}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>


<div class="card p-4">

    <h5>Analisis Keuangan</h5>

    <ul>

        <li>Total pemasukan tahun ini sebesar <b>Rp {{number_format($pemasukan)}}</b></li>

        <li>Total pengeluaran sebesar <b>Rp {{number_format($pengeluaran)}}</b></li>

        <li>Saldo keuangan saat ini adalah <b>Rp {{number_format($saldo)}}</b></li>

    </ul>

</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    new Chart(document.getElementById('chartKeuangan'),{type:'bar',data:{labels:{!! json_encode($bulan) !!},
    datasets:[
        {
            label:'Pemasukan',
            data:{!! json_encode($dataPemasukan) !!},
            backgroundColor:'#4CAF50'
        },
        {
            label:'Pengeluaran',
            data:{!! json_encode($dataPengeluaran) !!},
            backgroundColor:'#F44336'
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{
                beginAtZero:true,
                ticks:{
                    callback:function(value){
                        return 'Rp '+value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

</script>

@endsection