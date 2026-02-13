<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengunjungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // always show all entries ordered by date and time
        $pengunjung = Pengunjung::with('anggota')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->paginate(10);

        $anggotaList = Anggota::where('status', 'aktif')->get();

        // statistics
        $totalHariIni = Pengunjung::whereDate('tanggal_kunjungan', now())->count();
        $totalBulanIni = Pengunjung::whereMonth('tanggal_kunjungan', now())->whereYear('tanggal_kunjungan', now())->count();
        $totalSemua = Pengunjung::count();

        return view('admin.pengunjung.index', compact(
            'pengunjung', 'anggotaList',
            'totalHariIni', 'totalBulanIni', 'totalSemua'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // not used since form is embedded in index
        return redirect()->route('admin.pengunjung.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_kunjungan' => 'required|date',
            'jam_masuk' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        Pengunjung::create($data);

        return redirect()->route('admin.pengunjung.index')
            ->with('success', 'Data pengunjung berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // not implemented, not needed
        return redirect()->route('admin.pengunjung.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visit = Pengunjung::findOrFail($id);
        $anggotaList = Anggota::where('status', 'aktif')->get();
        return view('admin.pengunjung.edit', compact('visit', 'anggotaList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $visit = Pengunjung::findOrFail($id);

        $data = $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_kunjungan' => 'required|date',
            'jam_masuk' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        $visit->update($data);

        return redirect()->route('admin.pengunjung.index')
            ->with('success', 'Data pengunjung berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visit = Pengunjung::findOrFail($id);
        $visit->delete();

        return redirect()->route('admin.pengunjung.index')
            ->with('success', 'Data pengunjung berhasil dihapus');
    }

    /**
     * Display chart data for pengunjung grouped by date.
     *
     * @return \Illuminate\Http\Response
     */
    public function grafik(Request $request)
    {
        // month filtering, default current month
        $month = $request->get('bulan', now()->format('Y-m'));
        // calculate stats
        $totalToday = Pengunjung::whereDate('tanggal_kunjungan', now())->count();
        $totalMonth = Pengunjung::whereMonth('tanggal_kunjungan', \Carbon\Carbon::parse($month)->month)
                        ->whereYear('tanggal_kunjungan', \Carbon\Carbon::parse($month)->year)
                        ->count();

        $query = Pengunjung::select('tanggal_kunjungan', DB::raw('count(*) as total'))
            ->whereMonth('tanggal_kunjungan', \Carbon\Carbon::parse($month)->month)
            ->whereYear('tanggal_kunjungan', \Carbon\Carbon::parse($month)->year)
            ->groupBy('tanggal_kunjungan')
            ->orderBy('tanggal_kunjungan');

        $data = $query->get();

        // prepare arrays for chart
        $labels = $data->pluck('tanggal_kunjungan')->map(function($d) {
            return \Carbon\Carbon::parse($d)->format('Y-m-d');
        });
        $totals = $data->pluck('total');

        return view('admin.pengunjung.grafik', compact('data', 'labels', 'totals', 'month', 'totalToday', 'totalMonth'));
    }
}
