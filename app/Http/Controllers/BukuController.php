<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::all();
        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        Buku::create($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        Buku::findOrFail($id)->update($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        Buku::findOrFail($id)->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }
}
