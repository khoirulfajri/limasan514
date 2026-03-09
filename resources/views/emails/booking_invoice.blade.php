<h2>Pembayaran diterima</h2>

<p>Halo {{ $booking->nama }}</p>

<p>Bukti pembayaran untuk booking berikut sudah diterima.</p>

<p>Kode Booking : {{ $booking->kode_booking }}</p>

<p>Check In : {{ $booking->check_in }}</p>

<p>Check Out : {{ $booking->check_out }}</p>

<p>Total : Rp {{ number_format($booking->total_harga) }}</p>

<p>Kami akan segera memverifikasi pembayaran Anda.</p>