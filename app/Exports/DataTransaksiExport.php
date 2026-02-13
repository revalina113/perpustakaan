<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class DataTransaksiExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * Return a collection of rows that will be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // grab all peminjaman records with related anggota and buku
        $items = Peminjaman::with(['anggota', 'buku'])
            ->orderBy('created_at', 'desc')
            ->get();

        // transform to the desired row structure
        return $items->map(function ($p, $index) {
            return [
                'no'                 => $index + 1,
                'nama_siswa'         => optional($p->anggota)->nama,
                'nis'                => optional($p->anggota)->nis,
                'judul_buku'         => optional($p->buku)->judul,
                'tanggal_pinjam'     => $p->tanggal_pinjam ? $p->tanggal_pinjam->format('Y-m-d') : null,
                'tanggal_jatuh_tempo'=> $p->tanggal_jatuh_tempo ? $p->tanggal_jatuh_tempo->format('Y-m-d') : null,
                'tanggal_kembali'    => $p->tanggal_kembali ? $p->tanggal_kembali->format('Y-m-d') : null,
                'status'             => $p->status,
                'total_denda'        => $p->total_denda,
            ];
        });
    }

    /**
     * Headings for the exported spreadsheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NIS',
            'Judul Buku',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Status',
            'Total Denda',
        ];
    }
}
