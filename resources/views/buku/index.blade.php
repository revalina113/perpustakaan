<x-app-layout>
    <h1 class="text-2xl font-bold mb-6">Data Buku</h1>

    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.buku.create') }}"
       class="inline-block mb-4 px-4 py-2 bg-blue-900 text-white rounded">
        + Tambah Buku
    </a>

    <table class="w-full border">
        <thead class="bg-slate-100">
            <tr>
                <th class="border p-2">Judul</th>
                <th class="border p-2">Penulis</th>
                <th class="border p-2">Stok</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $item)
                <tr>
                    <td class="border p-2">{{ $item->judul }}</td>
                    <td class="border p-2">{{ $item->penulis }}</td>
                    <td class="border p-2">{{ $item->stok }}</td>
                    <td class="border p-2">
                        <a href="{{ route('admin.buku.edit', $item) }}" class="text-blue-600">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
