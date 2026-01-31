<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Anggota::query();

        // Search: if input is numeric -> search NIS only; if single token (no spaces) -> search username only; otherwise search nama
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);

            if (ctype_digit($search)) {
                // Numeric search -> NIS
                $query->where('nis', 'like', "%$search%");
            } elseif (strpos($search, ' ') === false) {
                // Single token without spaces -> treat as username
                $query->where('username', 'like', "%$search%");
            } else {
                // Otherwise, search by name
                $query->where('nama', 'like', "%$search%");
            }
        }

        // Filter status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $anggota = $query->paginate(10);

        return view('admin.anggota.index', compact('anggota'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:anggota,nis',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . $validator->errors()->first()]);
        }

        try {
            // Create Anggota
            $anggota = Anggota::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'username' => $request->username,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'status' => $request->status,
            ]);

            // Create User for login
            User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => null, // email nullable
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'status' => $request->status,
                'anggota_id' => $anggota->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Anggota berhasil ditambahkan']);
        } catch (\Exception $e) {
            Log::error('Error saving anggota: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $anggota = Anggota::findOrFail($id);
        return response()->json(['success' => true, 'data' => $anggota]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $anggota = Anggota::findOrFail($id);

        // Find existing user
        $user = User::where('username', $anggota->username)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:anggota,nis,' . $id,
            'username' => 'required|string|unique:users,username,' . $user->id . ',id',
            'password' => 'nullable|string|min:6',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . $validator->errors()->first()]);
        }

        try {
            // Update Anggota
            $anggota->update([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'username' => $request->username,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'status' => $request->status,
            ]);

            // Update User
            $user->update([
                'name' => $request->nama,
                'username' => $request->username,
                'status' => $request->status,
            ]);
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            return response()->json(['success' => true, 'message' => 'Anggota berhasil diupdate']);
        } catch (\Exception $e) {
            Log::error('Error updating anggota: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();

        return response()->json(['success' => true, 'message' => 'Anggota berhasil dihapus']);
    }

    /**
     * Toggle status of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->status = $anggota->status === 'aktif' ? 'nonaktif' : 'aktif';
        $anggota->save();

        return response()->json(['success' => true, 'message' => 'Status anggota berhasil diubah', 'status' => $anggota->status]);
    }
}
