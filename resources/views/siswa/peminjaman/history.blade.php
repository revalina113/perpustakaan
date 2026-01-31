@extends('layouts.siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Peminjaman</h1>
        <p class="text-gray-600 mt-1">Riwayat lengkap peminjaman Anda. Klik "Cetak Resi" untuk mendapatkan bukti peminjaman (PDF).</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        @if($riwayat->count() > 0)
        <div class="space-y-4">
            @foreach($riwayat as $pinjam)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-lg">{{ optional($pinjam->buku)->judul ?? 'Judul tidak tersedia' }} @if(!$pinjam->buku)<span class="ml-2 inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">Buku dihapus</span>@endif</h3>
                        <p class="text-gray-600 text-sm">Penulis: {{ optional($pinjam->buku)->penulis ?? '-' }}</p>
                        <p class="text-gray-600 text-sm">Tanggal Pinjam: {{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d M Y') : '-' }}</p>
                        <p class="text-gray-600 text-sm">Jatuh Tempo: {{ $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('d M Y') : '-' }}</p>
                        <p class="text-gray-600 text-sm">Status: <span class="font-semibold">{{ ucfirst($pinjam->status) }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Denda:</p>
                        <p class="text-red-600 font-semibold mb-3">Rp{{ number_format($pinjam->denda,0,',','.') }}</p>

                        <div class="space-y-2">
                            <a href="{{ route('siswa.peminjaman.resi', $pinjam->id) }}" target="_blank" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Lihat Resi</a>
                            <a href="{{ route('siswa.peminjaman.resi.download', $pinjam->id) }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Download Resi</a>
                            @if($pinjam->status === 'dipinjam')
                                <a href="{{ route('siswa.pengembalian.index') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Kembalikan</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $riwayat->links() }}
        </div>
        @else
        <p class="text-gray-600">Belum ada riwayat peminjaman.</p>
        @endif
    </div>
</div>
@endsection