<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataTransaksiExport;

class TransaksiController extends Controller
{
    public function index()
    {
        // Get all transactions with relationships
        $allPeminjaman = Peminjaman::with(['anggota', 'buku'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics based on actual status
        // Status calculation: dikembalikan = status='dikembalikan', terlambat = status='dipinjam' AND tanggal_jatuh_tempo < now, dipinjam = status='dipinjam' AND tanggal_jatuh_tempo >= now
        $totalTransaksi = $allPeminjaman->count();
        $sudahDikembalikan = $allPeminjaman->where('status', 'dikembalikan')->count();
        $terlambat = $allPeminjaman->where('status', 'dipinjam')->filter(fn($p) => $p->hari_terlambat > 0)->count();
        $sedangDipinjam = $allPeminjaman->where('status', 'dipinjam')->filter(fn($p) => $p->hari_terlambat == 0)->count();

        // Paginate the collection manually using LengthAwarePaginator
        $page = request()->get('page', 1);
        $perPage = 10;
        $items = $allPeminjaman->values()->all();
        $total = count($items);
        $items = array_slice($items, ($page - 1) * $perPage, $perPage);
        
        $peminjaman = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('admin.transaksi.index', compact(
            'totalTransaksi',
            'sedangDipinjam',
            'sudahDikembalikan',
            'terlambat',
            'peminjaman'
        ));
    }

    /**
     * Export transaksi data to an Excel file.
     *
     * Only administrators can access this route since the controller
     * is already protected by the isAdmin middleware on the route group.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $fileName = 'data_transaksi_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new DataTransaksiExport, $fileName);
    }

    public function markAsReturned($id)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status === 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi sudah dikembalikan'
                ], 400);
            }

            // Update peminjaman status
            $peminjaman->update(['status' => 'dikembalikan', 'tanggal_kembali' => now()]);

            // Safely increment book stock if the book exists
            if ($peminjaman->buku) {
                $peminjaman->buku->increment('stok');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditandai sebagai dikembalikan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku', 'pembayaranDenda'])->findOrFail($id);

        // If the request expects JSON (AJAX), return JSON for modal or API usage
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $peminjaman
            ]);
        }

        // For normal browser navigation, return the HTML detail view
        return view('admin.transaksi.show', compact('peminjaman'));
    }

    public function destroy($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Only allow deletion if book is returned
            if ($peminjaman->status === 'dikembalikan') {
                $peminjaman->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus transaksi yang masih dalam peminjaman'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $status = $request->get('status');

        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->when($query, function ($q) use ($query) {
                $q->whereHas('anggota', function ($subQ) use ($query) {
                    $subQ->where('nama', 'like', "%{$query}%");
                })->orWhereHas('buku', function ($subQ) use ($query) {
                    $subQ->where('judul', 'like', "%{$query}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Apply status filter based on synchronized status calculation
        if ($status) {
            $peminjaman = $peminjaman->filter(function ($p) use ($status) {
                if ($status === 'dikembalikan') {
                    return $p->status === 'dikembalikan';
                } elseif ($status === 'terlambat') {
                    return $p->status === 'dipinjam' && $p->hari_terlambat > 0;
                } elseif ($status === 'dipinjam') {
                    return $p->status === 'dipinjam' && $p->hari_terlambat == 0;
                }
                return true;
            });
        }

        // Paginate manually after filtering using LengthAwarePaginator
        $page = $request->get('page', 1);
        $perPage = 10;
        $items = $peminjaman->values()->all();
        $total = count($items);
        $items = array_slice($items, ($page - 1) * $perPage, $perPage);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $paginator
        ]);
    }

    public function create()
    {
        $anggota = \App\Models\Anggota::where('status', 'aktif')->get();
        $buku = \App\Models\Buku::where('stok', '>', 0)->get();
        $aturan = \App\Models\AturanPeminjaman::aktif();

        return view('admin.transaksi.create', compact('anggota', 'buku', 'aturan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date|before_or_equal:today',
        ]);

        $buku = \App\Models\Buku::findOrFail($request->buku_id);
        $aturan = \App\Models\AturanPeminjaman::aktif();

        if ($buku->stok <= 0) {
            return back()->withErrors(['buku_id' => 'Buku tidak tersedia (stok habis)']);
        }

        // Check if member already has this book borrowed
        $existingLoan = Peminjaman::where('anggota_id', $request->anggota_id)
            ->where('buku_id', $request->buku_id)
            ->where('status', 'dipinjam')
            ->first();

        if ($existingLoan) {
            return back()->withErrors(['buku_id' => 'Anggota sudah meminjam buku ini']);
        }

        try {
            DB::beginTransaction();

            $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
            $tanggalJatuhTempo = $tanggalPinjam->copy()->addDays($aturan->lama_peminjaman);

            $peminjaman = Peminjaman::create([
                'anggota_id' => $request->anggota_id,
                'buku_id' => $request->buku_id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'status' => 'dipinjam',
            ]);

            // Decrease book stock
            $buku->decrement('stok');

            DB::commit();

            return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi peminjaman berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Only allow editing if not returned
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('admin.transaksi.index')->with('error', 'Tidak dapat mengedit transaksi yang sudah dikembalikan');
        }

        $anggota = \App\Models\Anggota::where('status', 'aktif')->get();
        $buku = \App\Models\Buku::where('stok', '>', 0)->orWhere('id', $peminjaman->buku_id)->get();
        $aturan = \App\Models\AturanPeminjaman::aktif();

        return view('admin.transaksi.edit', compact('peminjaman', 'anggota', 'buku', 'aturan'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('admin.transaksi.index')->with('error', 'Tidak dapat mengedit transaksi yang sudah dikembalikan');
        }

        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date|before_or_equal:today',
        ]);

        $buku = \App\Models\Buku::findOrFail($request->buku_id);
        $aturan = \App\Models\AturanPeminjaman::aktif();

        // Check stock if changing book
        if ($request->buku_id != $peminjaman->buku_id) {
            if ($buku->stok <= 0) {
                return back()->withErrors(['buku_id' => 'Buku tidak tersedia (stok habis)']);
            }

            // Check if member already has this book borrowed (excluding current loan)
            $existingLoan = Peminjaman::where('anggota_id', $request->anggota_id)
                ->where('buku_id', $request->buku_id)
                ->where('status', 'dipinjam')
                ->where('id', '!=', $id)
                ->first();

            if ($existingLoan) {
                return back()->withErrors(['buku_id' => 'Anggota sudah meminjam buku ini']);
            }
        }

        try {
            DB::beginTransaction();

            $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
            $tanggalJatuhTempo = $tanggalPinjam->copy()->addDays($aturan->lama_peminjaman);

            // If changing book, adjust stock
            if ($request->buku_id != $peminjaman->buku_id) {
                // Return old book stock
                $peminjaman->buku->increment('stok');
                // Decrease new book stock
                $buku->decrement('stok');
            }

            $peminjaman->update([
                'anggota_id' => $request->anggota_id,
                'buku_id' => $request->buku_id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            ]);

            DB::commit();

            return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi peminjaman berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}