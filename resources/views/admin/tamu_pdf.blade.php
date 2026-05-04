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
            padding: 6px;
            font-size: 11px;
        }

        th {
            background: #ddd;
        }

        .text-center {
            text-align: center;
        }

        /* WARNA BERDASARKAN SUMBER */
        .website {
            background: #d4edda;
        }

        /* hijau */
        .booking {
            background: #cce5ff;
        }

        /* biru */
        .agoda {
            background: #ffeeba;
        }

        /* kuning */
        .tiket {
            background: #f8d7da;
        }

        /* merah */
        .manual {
            background: #e2e3e5;
        }

        /* abu */
    </style>
</head>

<body>

    <h3 class="text-center">DATA TAMU LIMASAN 514</h3>

    <p class="text-center" style="font-size:12px; margin-top:-10px;">
        {{ $keterangan }}
    </p>

    <table style="margin-bottom:10px; border:none;">
        <tr>
            <td style="border:none; font-size:11px;">
                <b>Keterangan Warna:</b>
            </td>

            <td style="border:none; background:#d4edda; padding:5px;">Website</td>
            <td style="border:none; background:#cce5ff; padding:5px;">Booking.com</td>
            <td style="border:none; background:#ffeeba; padding:5px;">Agoda</td>
            <td style="border:none; background:#f8d7da; padding:5px;">Tiket.com</td>
            <td style="border:none; background:#e2e3e5; padding:5px;">On The Spot</td>
        </tr>
    </table>

    <table>

        <tr>
            <th>Tanggal</th>
            <th>Harga</th>
            @foreach($rooms as $r)
            <th>Room {{ $r->nomor_kamar }}</th>
            @endforeach
        </tr>

        @foreach($periode as $tgl)
        <tr>

            <td class="text-center">
                {{ $tgl->format('d/m/Y') }}
            </td>

            {{-- TOTAL HARGA HARIAN --}}
            <td class="text-center">
                @php
                $total = 0;
                @endphp

                @foreach($data as $b)
                @if($tgl >= \Carbon\Carbon::parse($b->check_in) && $tgl < \Carbon\Carbon::parse($b->check_out))
                    @php $total += $b->total_harga; @endphp
                    @endif
                    @endforeach

                    {{ number_format($total,0,',','.') }}
            </td>

            {{-- LOOP ROOM --}}
            @foreach($rooms as $r)

            @php
            $found = null;
            @endphp

            @foreach($data as $b)
            @if(
            $b->rooms->contains('id', $r->id) &&
            $tgl >= \Carbon\Carbon::parse($b->check_in) &&
            $tgl < \Carbon\Carbon::parse($b->check_out)
                )
                @php
                $found = $b;
                @endphp
                @endif
                @endforeach

                <td class="
                @if($found)
                    @if($found->sumber == 'website') website
                    @elseif($found->sumber == 'booking.com') booking
                    @elseif($found->sumber == 'agoda') agoda
                    @elseif($found->sumber == 'tiket.com') tiket
                    @else manual
                    @endif
                @endif
            ">

                    @if($found)
                    {{ $found->nama }}
                    @else
                    -
                    @endif

                </td>

                @endforeach

        </tr>
        @endforeach

    </table>

</body>

</html>