<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
            color: #000;
        }

        h2,
        h3 {
            text-align: center;
            margin-bottom: 5px;
        }

        hr {
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background: #f0f0f0;
        }

        th,
        td {
            padding: 8px;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .section-title {
            margin-top: 25px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>

</head>

<body>

    <h2>LAPORAN LABA RUGI</h2>
    <h3>Limasan 514</h3>

    <p class="text-center">
        @if(request('bulan'))
        Periode {{ date('F', mktime(0,0,0,request('bulan'),1)) }} {{ request('tahun') }}
        @else
        per 31 Desember {{ request('tahun') ?? date('Y') }}
        @endif
    </p>

    <hr>

    <!-- ======================
     LABA RUGI
    ====================== -->

    <div class="section-title">
        Laporan Laba Rugi
    </div>

    <table>

        <tr>
            <th class="text-center">Tanggal</th>
            <th>Keterangan</th>
            <th class="text-right">Pemasukan (Rp)</th>
            <th class="text-right">Pengeluaran (Rp)</th>
        </tr>

        {{-- ======================
        PEMASUKAN
        ====================== --}}
        @php $totalPemasukan = 0; @endphp

        @foreach($data->where('tipe','pemasukan') as $row)
        <tr>
            <td class="text-center">
                {{ date('d-m-Y', strtotime($row->tanggal)) }}
            </td>
            <td>{{ $row->keterangan }}</td>
            <td class="text-right">
                {{ number_format($row->jumlah,0,',','.') }}
            </td>
            <td>-</td>
        </tr>

        @php $totalPemasukan += $row->jumlah; @endphp
        @endforeach

        <tr>
            <th colspan="2">Total Pemasukan</th>
            <th class="text-right">
                {{ number_format($totalPemasukan,0,',','.') }}
            </th>
            <th></th>
        </tr>


        {{-- ======================
        PENGELUARAN
        ====================== --}}
        @php $totalPengeluaran = 0; @endphp

        @foreach($data->where('tipe','pengeluaran') as $row)
        <tr>
            <td class="text-center">
                {{ date('d-m-Y', strtotime($row->tanggal)) }}
            </td>
            <td>{{ $row->keterangan }}</td>
            <td>-</td>
            <td class="text-right">
                ({{ number_format($row->jumlah,0,',','.') }})
            </td>
        </tr>

        @php $totalPengeluaran += $row->jumlah; @endphp
        @endforeach

        <tr>
            <th colspan="2">Total Pengeluaran</th>
            <th></th>
            <th class="text-right">
                ({{ number_format($totalPengeluaran,0,',','.') }})
            </th>
        </tr>


        {{-- ======================
        LABA / RUGI
        ====================== --}}
        @php
        $saldo = $totalPemasukan - $totalPengeluaran;
        @endphp

        <tr style="background: #ffff00;">
            <th colspan="2">
                {{ $saldo >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
            </th>
            <th colspan="2" class="text-right">
                {{ number_format(abs($saldo),0,',','.') }}
            </th>
        </tr>

    </table>


    <!-- ======================
     NARASI
    ====================== -->


    <br><br>

    <table style="width:100%; border:none;">

        <tr>

            <td style="border:none; width:60%;"></td>

            <td style="border:none; text-align:center;">

                {{ date('d F Y') }} <br>

                Mengetahui,<br><br><br>

                <strong>Admin</strong>

            </td>

        </tr>

    </table>

</body>

</html>