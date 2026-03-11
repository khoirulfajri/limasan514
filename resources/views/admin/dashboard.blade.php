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
        <div class="mb-3">

            <select id="modeGrafik" class="form-select w-auto">

                <option value="total">Keseluruhan</option>

                <option value="bulan">Bulanan</option>

            </select>

        </div>

        <canvas id="grafik"></canvas>
    </div>
</div>

<script>
    const bulan = [];
const pemasukan = [];
const pengeluaran = [];

@foreach($grafik as $g)

let namaBulan = '';

switch({{$g->bulan}}){
case 1: namaBulan='Jan'; break;
case 2: namaBulan='Feb'; break;
case 3: namaBulan='Mar'; break;
case 4: namaBulan='Apr'; break;
case 5: namaBulan='Mei'; break;
case 6: namaBulan='Jun'; break;
case 7: namaBulan='Jul'; break;
case 8: namaBulan='Agu'; break;
case 9: namaBulan='Sep'; break;
case 10: namaBulan='Okt'; break;
case 11: namaBulan='Nov'; break;
case 12: namaBulan='Des'; break;
}

bulan.push(namaBulan);
pemasukan.push({{$g->pemasukan}});
pengeluaran.push({{$g->pengeluaran}});

@endforeach


let chart = new Chart(document.getElementById('grafik'),{

type:'bar',

data:{
labels:['Pemasukan','Pengeluaran'],
datasets:[{
label:'Total',
data:[{{$totalPemasukan}},{{$totalPengeluaran}}],
backgroundColor:['green','red']
}]
}

});


document.getElementById('modeGrafik').addEventListener('change',function(){

if(this.value === 'total'){

chart.data.labels=['Pemasukan','Pengeluaran'];

chart.data.datasets=[
{
label:'Total',
data:[{{$totalPemasukan}},{{$totalPengeluaran}}],
backgroundColor:['green','red']
}
];

}

if(this.value === 'bulan'){

chart.data.labels=bulan;

chart.data.datasets=[

{
label:'Pemasukan',
data:pemasukan,
backgroundColor:'green'
},

{
label:'Pengeluaran',
data:pengeluaran,
backgroundColor:'red'
}

];

}

chart.update();

});

</script>

@endsection