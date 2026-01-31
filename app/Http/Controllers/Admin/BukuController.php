<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::latest()->get();
        return view('admin.buku.index', compact('buku'));
    }

    public function create()
    {
        return view('admin.buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'penulis'  => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun'    => 'required|digits:4',
            'stok'     => 'required|integer|min:0',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('buku', $filename, 'public');
            $data['gambar'] = $path;
        }

        Buku::create($data);

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'penulis'  => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun'    => 'required|digits:4',
            'stok'     => 'required|integer|min:0',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($buku->gambar && Storage::disk('public')->exists($buku->gambar)) {
                Storage::disk('public')->delete($buku->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('buku', $filename, 'public');
            $data['gambar'] = $path;
        }

        $buku->update($data);

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Buku $buku)
    {
        // Delete image if exists
        if ($buku->gambar && Storage::disk('public')->exists($buku->gambar)) {
            Storage::disk('public')->delete($buku->gambar);
        }

        $buku->delete();
        return back()->with('success', 'Buku berhasil dihapus');
    }
}
