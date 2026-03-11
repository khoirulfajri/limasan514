<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        p {
            margin: 10px 0;
        }
        .details {
            margin-top: 20px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 8px;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
        .icon-success {
            text-align: center;
            margin-bottom: 20px;
        }
        .icon-success span {
            font-size: 50px;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Ikon Berhasil -->
        <div class="icon-success">
            <span>&#10004;</span>
        </div>

        <h2>Pembayaran Diterima</h2>

        <p>Halo <strong>{{ $booking->nama }}</strong>,</p>

        <p>Bukti pembayaran untuk booking berikut telah kami terima. Berikut adalah detailnya:</p>

        <div class="details">
            <p><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</p>
            <p><strong>Check In:</strong> {{ $booking->check_in }}</p>
            <p><strong>Check Out:</strong> {{ $booking->check_out }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($booking->total_harga) }}</p>
        </div>

        <p>Kami akan segera memverifikasi pembayaran Anda. Terima kasih telah mempercayai layanan kami.</p>

        <!-- Tombol Kembali ke Beranda -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px; font-size: 16px;">Kembali ke Beranda</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Limasan514. Semua Hak Dilindungi.</p>
        </div>
    </div>
</body>
</html>