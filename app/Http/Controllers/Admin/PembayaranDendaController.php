<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranDenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranDendaController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranDenda::with(['peminjaman.buku', 'anggota']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status_pembayaran', $request->status);
        }

        // Search berdasarkan nama siswa atau judul buku
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('peminjaman.buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            $pembayaranDenda = $query->orderBy('created_at', 'desc')->paginate(10);
            return response()->json($pembayaranDenda);
        }

        // Jika request biasa, kembalikan view
        $pembayaranDenda = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pembayaran-denda.index', compact('pembayaranDenda'));
    }

    public function show($id)
    {
        $pembayaran = PembayaranDenda::with(['peminjaman.buku', 'anggota'])->findOrFail($id);

        return view('admin.pembayaran-denda.show', compact('pembayaran'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,lunas,ditolak',
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        $pembayaran = PembayaranDenda::findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        $message = match($request->status) {
            'lunas' => 'Pembayaran denda telah disetujui dan ditandai lunas.',
            'ditolak' => 'Pembayaran denda telah ditolak.',
            default => 'Status pembayaran telah diperbarui.'
        };

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function verifikasi($id)
    {
        $pembayaran = PembayaranDenda::findOrFail($id);

        $pembayaran->update(['status_pembayaran' => 'lunas']);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran denda telah diverifikasi dan ditandai lunas.'
        ]);
    }

    public function bulkVerifikasi(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:pembayaran_denda,id'
        ]);

        $ids = $request->ids;

        PembayaranDenda::whereIn('id', $ids)->update(['status_pembayaran' => 'lunas']);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran yang dipilih berhasil diverifikasi.'
        ]);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500'
        ]);

        $pembayaran = PembayaranDenda::findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran denda telah ditolak.'
        ]);
    }
}
