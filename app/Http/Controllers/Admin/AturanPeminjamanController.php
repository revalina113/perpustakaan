<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AturanPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AturanPeminjamanController extends Controller
{
    /**
     * Display the loan rules settings page
     */
    public function index()
    {
        $aturan = AturanPeminjaman::aktif();

        if (!$aturan) {
            // Create default rule if none exists
            $aturan = AturanPeminjaman::create([
                'lama_peminjaman' => 7,
                'denda_per_hari' => 1000,
                'deskripsi' => 'Aturan peminjaman default',
                'aktif' => true
            ]);
        }

        return view('admin.aturan-peminjaman.index', compact('aturan'));
    }

    /**
     * Update the loan rules
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lama_peminjaman' => 'required|integer|min:1|max:365',
            'denda_per_hari' => 'required|integer|min:0|max:100000',
            'deskripsi' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $aturan = AturanPeminjaman::aktif();

        if (!$aturan) {
            // Create new rule if none exists
            AturanPeminjaman::create([
                'lama_peminjaman' => $request->lama_peminjaman,
                'denda_per_hari' => $request->denda_per_hari,
                'deskripsi' => $request->deskripsi ?: 'Aturan peminjaman yang diperbarui',
                'aktif' => true
            ]);
        } else {
            // Update existing rule
            $aturan->update([
                'lama_peminjaman' => $request->lama_peminjaman,
                'denda_per_hari' => $request->denda_per_hari,
                'deskripsi' => $request->deskripsi ?: $aturan->deskripsi
            ]);
        }

        return back()->with('success', 'Aturan peminjaman berhasil diperbarui.');
    }
}
