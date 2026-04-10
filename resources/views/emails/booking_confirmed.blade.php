<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Dikonfirmasi</title>
</head>

<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0;">

    <div
        style="max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow: hidden;">

        <!-- Header -->
        <div
            style="background: linear-gradient(135deg, #28a745, #5fdc9d); color: #ffffff; padding: 25px; text-align: center;">
            <h2 style="margin: 0; font-size: 24px;">✅ Booking Dikonfirmasi</h2>
            <p style="margin: 5px 0 0; font-size: 14px;">Pesanan Anda telah berhasil dikonfirmasi</p>
        </div>

        <!-- Content -->
        <div style="padding: 25px;">
            <p style="font-size: 16px;">Halo <strong>{{ $booking->nama }}</strong>,</p>

            <p style="font-size: 15px; line-height: 1.6;">
                Kami dengan senang hati menginformasikan bahwa booking Anda telah <strong>berhasil
                    dikonfirmasi</strong>.
                Silakan simpan informasi berikut sebagai referensi saat check-in.
            </p>

            <!-- Booking Detail -->
            <div style="border: 1px solid #eee; border-radius: 10px; overflow: hidden; margin: 20px 0;">

                <div style="background: #f9fafb; padding: 15px; text-align: center;">
                    <p style="margin: 0; font-size: 13px; color: #888;">Kode Booking</p>
                    <h2 style="margin: 5px 0; color: #28a745;">{{ $booking->kode_booking }}</h2>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-top: 1px solid #eee;">Check In</td>
                        <td style="padding: 10px; border-top: 1px solid #eee; text-align: right;">
                            {{ $booking->check_in }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-top: 1px solid #eee;">Check Out</td>
                        <td style="padding: 10px; border-top: 1px solid #eee; text-align: right;">
                            {{ $booking->check_out }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-top: 1px solid #eee; font-weight: bold;">Total Pembayaran</td>
                        <td style="padding: 10px; border-top: 1px solid #eee; text-align: right; font-weight: bold;">
                            Rp {{ number_format($booking->total_harga) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-top: 1px solid #eee;">Status</td>
                        <td
                            style="padding: 10px; border-top: 1px solid #eee; text-align: right; color: #28a745; font-weight: bold;">
                            CONFIRMED
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Important Info -->
            <div style="background: #e8f8f0; padding: 15px; border-radius: 8px; font-size: 14px; color: #2e7d32;">
                <strong>Informasi Penting:</strong><br>
                • Harap tunjukkan kode booking saat check-in<br>
                • Datang sesuai tanggal yang telah dipilih<br>
                • Hubungi kami jika ada perubahan jadwal
            </div>

            <p style="font-size: 14px; margin-top: 20px;">
                Kami menantikan kedatangan Anda 😊
            </p>
        </div>

        <!-- Footer -->
        <div style="background: #f1f3f5; text-align: center; padding: 15px; font-size: 13px; color: #777;">
            <p style="margin: 0;">&copy; {{ date('Y') }} Limasan514</p>
            <p style="margin: 5px 0 0;">Selamat menikmati pengalaman menginap Anda ✨</p>
        </div>

    </div>

</body>

</html>