<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Buku
        </h2>
    </x-slot>

    <div class="max-w-3xl bg-white rounded-2xl shadow p-6">
        <form method="POST" action="{{ route('admin.buku.update', $buku->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-medium">Judul</label>
                <input type="text" name="judul" value="{{ $buku->judul }}" class="w-full mt-1 rounded-lg border-slate-300" required>
            </div>

            <div>
                <label class="text-sm font-medium">Penulis</label>
                <input type="text" name="penulis" value="{{ $buku->penulis }}" class="w-full mt-1 rounded-lg border-slate-300" required>
            </div>

            <div>
                <label class="text-sm font-medium">Penerbit</label>
                <input type="text" name="penerbit" value="{{ $buku->penerbit }}" class="w-full mt-1 rounded-lg border-slate-300" required>
            </div>

            <div>
                <label class="text-sm font-medium">Tahun</label>
                <input type="number" name="tahun" value="{{ $buku->tahun }}" class="w-full mt-1 rounded-lg border-slate-300" required>
            </div>

            <div>
                <label class="text-sm font-medium">Stok</label>
                <input type="number" name="stok" value="{{ $buku->stok }}" class="w-full mt-1 rounded-lg border-slate-300" required>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.buku.index') }}" class="px-4 py-2 border rounded-lg">
                    Batal
                </a>
                <button class="px-4 py-2 bg-blue-900 text-white rounded-lg">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
