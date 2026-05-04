@extends('admin.layout')

@section('content')

<h3>Data Tamu</h3>
<div class="alert alert-info">
    Total Booking: <b>{{ $data->count() }}</b> <br>
    Total Tamu: <b>{{ $data->sum('jumlah_tamu') }}</b>
</div>

<div class="mb-3">
    <a href="{{ route('admin.tamu.pdf', request()->query()) }}" class="btn btn-danger">
        Export PDF
    </a>
</div>

{{-- FILTER --}}

<form method="GET" class="row g-2 mb-3">

    {{-- SEARCH --}}
    <div class="col-md-2">
        <input type="text" name="search" value="{{request('search')}}" class="form-control"
            placeholder="Cari nama tamu">
    </div>

    {{-- TANGGAL --}}
    <div class="col-md-2">
        <input type="date" name="check_in" value="{{request('check_in')}}" class="form-control">
    </div>

    <div class="col-md-2">
        <input type="date" name="check_out" value="{{request('check_out')}}" class="form-control">
    </div>

    {{-- KAMAR --}}
    <div class="col-md-2">
        <select name="room" class="form-control">
            <option value="">Semua Kamar</option>
            @for($i=2; $i<=6; $i++) <option value="{{$i}}" {{request('room')==$i?'selected':''}}>
                Kamar {{$i}}
                </option>
                @endfor
        </select>
    </div>

    {{-- TODAY --}}
    <div class="col-md-2">
        <select name="today" class="form-control">
            <option value="">Semua</option>
            <option value="1" {{request('today')?'selected':''}}>
                Menginap Hari Ini
            </option>
        </select>
    </div>

    {{-- BUTTON --}}
    <div class="col-md-1">
        <button class="btn btn-primary w-100">Filter</button>
    </div>

    {{-- RESET --}}
    <div class="col-md-1">
        @if(request()->hasAny(['search','tipe','dari','sampai']))
        <a href="{{route('admin.tamu')}}" class="btn btn-outline-dark">
            Reset
        </a>
        @endif
    </div>

</form>

<table class="table table-bordered">

    <tr>
        <th>Nama</th>
        <th>Jumlah Tamu</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Kamar</th>
    </tr>

    @foreach($data as $b)
    <tr>
        <td>{{ $b->nama }}</td>

        <td>
            <span class="badge bg-info">
                {{ $b->jumlah_tamu }}
            </span>
        </td>

        <td>{{ date('d-m-Y', strtotime($b->check_in)) }}</td>

        <td>{{ date('d-m-Y', strtotime($b->check_out)) }}</td>

        <td>
            @foreach($b->rooms as $r)
            <span class="badge bg-primary">
                {{ $r->nomor_kamar }}
            </span>
            @endforeach
        </td>
    </tr>
    @endforeach

</table>

@endsection