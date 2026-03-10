<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>

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

    <h2>LAPORAN KEUANGAN</h2>
    <h3>Limasan 514</h3>

    <p class="text-center">
        Periode Tahun {{date('Y')}}
    </p>

    <hr>


    <!-- ======================
     TABEL LABA RUGI
====================== -->

    <div class="section-title">
        Laporan Laba Rugi
    </div>

    <table>

        <tr>
            <th>Keterangan</th>
            <th class="text-right">Jumlah (Rp)</th>
        </tr>

        <tr>
            <td>Total Pemasukan</td>
            <td class="text-right">
                {{number_format($pemasukan,0,',','.')}}
            </td>
        </tr>

        <tr>
            <td>Total Pengeluaran</td>
            <td class="text-right">
                {{number_format($pengeluaran,0,',','.')}}
            </td>
        </tr>

        <tr>

            <th>
                {{$saldo >= 0 ? 'Laba Bersih' : 'Rugi Bersih'}}
            </th>

            <th class="text-right">
                {{number_format(abs($saldo),0,',','.')}}
            </th>

        </tr>

    </table>



    <!-- ======================
     TABEL TRANSAKSI
====================== -->

    <div class="section-title">
        Rincian Transaksi Keuangan
    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th>Jumlah (Rp)</th>

            </tr>

        </thead>

        <tbody>

            @foreach($data as $index => $row)

            <tr>

                <td class="text-center">
                    {{$index+1}}
                </td>

                <td>
                    {{date('d-m-Y', strtotime($row->tanggal))}}
                </td>

                <td>
                    {{$row->keterangan}}
                </td>

                <td class="text-center">
                    {{ucfirst($row->tipe)}}
                </td>

                <td class="text-right">
                    {{number_format($row->jumlah,0,',','.')}}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>


    <!-- ======================
     NARASI
====================== -->

    <p style="margin-top:20px; text-align:justify; font-size:13px;">

        Berdasarkan laporan laba rugi yang disusun dari data transaksi keuangan,
        diperoleh total pemasukan sebesar

        <strong>
            Rp {{number_format($pemasukan,0,',','.')}}
        </strong>

        dan total pengeluaran sebesar

        <strong>
            Rp {{number_format($pengeluaran,0,',','.')}}
        </strong>.

        Dengan demikian, Limasan 514 memperoleh

        <strong>
            {{$saldo >= 0 ? 'laba bersih' : 'rugi bersih'}}
        </strong>

        sebesar

        <strong>
            Rp {{number_format(abs($saldo),0,',','.')}}
        </strong>

        pada periode tahun {{date('Y')}}.

    </p>


    <br><br>

    <table style="width:100%; border:none;">

        <tr>

            <td style="border:none; width:60%;"></td>

            <td style="border:none; text-align:center;">

                {{date('d F Y')}} <br>

                Mengetahui,<br><br><br>

                <strong>Admin</strong>

            </td>

        </tr>

    </table>

</body>

</html>