<div style="font-family: Arial, sans-serif; color: #222;">
    <h2>Pengingat: Peminjaman Terlambat</h2>
    <p>Halo {{ $peminjaman->anggota->nama ?? '-' }},</p>
    <p>Anda memiliki peminjaman yang melewati tanggal jatuh tempo:</p>

    <ul>
        <li>Nomor Resi: <strong>#{{ $peminjaman->id }}</strong></li>
        <li>Judul Buku: <strong>{{ optional($peminjaman->buku)->judul ?? '-' }}</strong></li>
        <li>Jatuh Tempo: <strong>{{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->format('d M Y') : '-' }}</strong></li>
        <li>Denda saat ini: <strong>Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</strong></li>
    </ul>

    <p>Silakan lakukan pembayaran denda di halaman <a href="{{ route('siswa.jatuh-tempo.index') }}">Jatuh Tempo & Denda</a> atau hubungi admin perpustakaan untuk bantuan.</p>

    <p>Terima kasih.<br>Perpustakaan</p>
</div>