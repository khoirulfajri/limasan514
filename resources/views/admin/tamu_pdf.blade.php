<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            font-size: 12px;
        }

        th {
            background: #eee;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h3 class="text-center">DATA TAMU LIMASAN 514</h3>

    <table>
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
            <td class="text-center">{{ $b->jumlah_tamu }}</td>
            <td>{{ date('d-m-Y', strtotime($b->check_in)) }}</td>
            <td>{{ date('d-m-Y', strtotime($b->check_out)) }}</td>
            <td>
                @foreach($b->rooms as $r)
                {{ $r->nomor_kamar }},
                @endforeach
            </td>
        </tr>
        @endforeach
    </table>

</body>

</html>