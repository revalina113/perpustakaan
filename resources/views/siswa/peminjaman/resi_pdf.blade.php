<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resi Peminjaman #{{ $peminjaman->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; }
        .header { text-align: center; margin-bottom: 20px; }
        .box { border: 1px solid #ddd; padding: 12px; border-radius: 6px; }
        .label { font-weight: bold; }
        .small { font-size: 12px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        td, th { padding: 6px; border: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display:flex; align-items:center; justify-content:center; gap:12px;">
            @if(!empty($logoPath))
                <div>
                    <img src="{{ $logoPath }}" alt="Logo" style="width:56px; height:56px; border-radius:6px; object-fit:cover;" />
                </div>
            @else
                <div style="width:56px; height:56px; border-radius:6px; background:#2b6cb0; color:white; display:flex; align-items:center; justify-content:center; font-weight:bold;">P</div>
            @endif
            <div>
                <h2 style="margin:0">Perpustakaan</h2>
                <div class="small">Bukti Peminjaman / Resi</div>
            </div>
        </div>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="label">No. Resi</td>
                <td>#{{ $peminjaman->id }}</td>
            </tr>
            <tr>
                <td class="label">Nama Peminjam</td>
                <td>{{ $peminjaman->anggota->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIS</td>
                <td>{{ $peminjaman->anggota->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Judul Buku</td>
                <td>{{ optional($peminjaman->buku)->judul ?? 'Judul tidak tersedia' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pinjam</td>
                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Jatuh Tempo</td>
                <td>{{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Kembali</td>
                <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>{{ ucfirst($peminjaman->status) }}</td>
            </tr>
            <tr>
                <td class="label">Total Denda</td>
                <td>Rp{{ number_format($peminjaman->denda,0,',','.') }}</td>
            </tr>
        </table>

        @if($peminjaman->pembayaranDenda->count() > 0)
            <div style="margin-top:12px;">
                <strong>Riwayat Pembayaran Denda:</strong>
                <ul>
                    @foreach($peminjaman->pembayaranDenda as $pd)
                        <li>{{ $pd->tanggal_bayar ? $pd->tanggal_bayar->format('d M Y') : '-' }} — Rp{{ number_format($pd->jumlah_denda,0,',','.') }} ({{ $pd->status_text }})</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display:flex; gap:12px; align-items:center; margin-top:20px; font-size:12px; color:#666;">
            <div>
                <em>Terima kasih telah menggunakan layanan perpustakaan.</em>
            </div>
            @if(!empty($verifyUrl))
            <div style="margin-left:auto; text-align:center;">
                <div class="small">Verifikasi Resi</div>
                <img src="https://chart.googleapis.com/chart?chs=180x180&cht=qr&chl={{ urlencode($verifyUrl) }}" alt="QR Verifikasi" style="width:120px; height:120px;" />
                <div class="small">Scan untuk verifikasi (berlaku 30 hari)</div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>