@extends('admin.layout')

@section('content')

{{-- Notifikasi Booking --}}
@if($pendingBooking > 0)

<div class="alert alert-warning">
    Ada {{$pendingBooking}} booking menunggu konfirmasi
</div>

@endif


<div class="row">

    {{-- <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total User</h5>
                <h3>{{$totalUser}}</h3>
            </div>
        </div>
    </div> --}}

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Total Booking</h5>
                <h3>{{$totalBooking}}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Pemasukan</h5>
                <h4>Rp {{number_format($totalPemasukan)}}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>Pengeluaran</h5>
                <h4>Rp {{number_format($totalPengeluaran)}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <h5>Saldo</h5>
                <h4>Rp {{number_format($saldo)}}</h4>
            </div>
        </div>
    </div>

</div>


<div class="card mt-4 shadow">
    <div class="card-body">
        <h3>Analisis Keuangan</h3>
        
        {{-- menampilkan grafik --}}
        <canvas id="chartSumber"></canvas>
    </div>
</div>
{{-- script untuk grafik --}}
<script>
    const data = @json($dataSumber);
    
    const labels = Object.keys(data);
    const values = Object.values(data);
    
    new Chart(document.getElementById('chartSumber'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Booking',
                data: values
            }]
        }
    });
</script>

@endsection