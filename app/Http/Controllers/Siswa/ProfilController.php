<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $anggota = Auth::user()->anggota;
        if (!$anggota) {
            return redirect()->route('siswa.profil')->with('error', 'Data anggota tidak ditemukan.');
        }

        $anggota->no_hp = $request->no_hp;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($anggota->foto && Storage::disk('public')->exists($anggota->foto)) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $path = $request->file('foto')->store('foto-anggota', 'public');
            $anggota->foto = $path;
        }

        $anggota->save();

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
