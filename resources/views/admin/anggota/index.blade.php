@extends('layouts.admin')

@section('content')


    <!-- Main Content -->
    <div class="pt-0 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button onclick="toggleSidebar()" class="fixed top-4 left-4 z-50 bg-gray-800 text-white p-2 rounded-lg shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Kelola Anggota</h2>
                        <p class="text-gray-600 mt-1">Manajemen data siswa perpustakaan</p>
                    </div>
                    <button onclick="openAddModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Anggota
                    </button>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <form id="searchForm" method="GET" action="" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari NIS / Username / Nama (tekan Enter untuk cari)"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   onkeyup="filterAnggota(event)">
                            <small class="text-xs text-gray-400 block mt-1">Tip: ketik NIS (angka) untuk mencari NIS, ketik username tanpa spasi untuk username, atau ketik nama lengkap untuk pencarian nama.</small>
                        </div>
                    </div>
                    <div class="sm:w-48">
                        <select id="statusFilter" name="status" onchange="document.getElementById('searchForm').submit()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="sm:w-32 flex items-center">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md">Cari</button>
                    </div>
                </form>
            </div>

            <!-- Anggota Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Anggota</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="anggotaTable" class="bg-white divide-y divide-gray-200">
                            @foreach($anggota as $index => $a)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $anggota->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $a->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->kelas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->no_hp ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($a->status === 'aktif')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="edit-anggota text-blue-600 hover:text-blue-900" data-id="{{ $a->id }}" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button class="toggle-status text-yellow-600 hover:text-yellow-900" data-id="{{ $a->id }}" title="Toggle Status">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                            </svg>
                                        </button>
                                        <button class="delete-anggota text-red-600 hover:text-red-900" data-id="{{ $a->id }}" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $anggota->firstItem() }}</span> sampai <span class="font-medium">{{ $anggota->lastItem() }}</span> dari <span class="font-medium">{{ $anggota->total() }}</span> hasil
                        </div>
                        <div class="flex space-x-2">
                            {{ $anggota->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="anggotaModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold">Tambah Anggota</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="anggotaForm">
                <input type="hidden" id="anggotaId" name="id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                    <input type="text" id="nama" name="nama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">NIS</label>
                    <input type="text" id="nis" name="nis" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <input type="text" id="kelas" name="kelas" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" id="no_hp" name="no_hp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="button" onclick="saveAnggota()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Toggle sidebar for mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Anggota';
        document.getElementById('anggotaForm').reset();
        document.getElementById('anggotaId').value = '';
        // Set default username to NIS
        document.getElementById('nis').addEventListener('input', function() {
            document.getElementById('username').value = this.value;
        });
        const modal = document.getElementById('anggotaModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(id) {
        fetch(`/admin/anggota/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const a = data.data;
                    document.getElementById('modalTitle').textContent = 'Edit Anggota';
                    document.getElementById('anggotaId').value = a.id;
                    document.getElementById('nama').value = a.nama;
                    document.getElementById('nis').value = a.nis;
                    document.getElementById('username').value = a.username || a.nis;
                    document.getElementById('kelas').value = a.kelas;
                    document.getElementById('jenis_kelamin').value = a.jenis_kelamin;
                    document.getElementById('no_hp').value = a.no_hp || '';
                    document.getElementById('status').value = a.status;
                    const modal = document.getElementById('anggotaModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            });
    }

    function closeModal() {
        const modal = document.getElementById('anggotaModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function saveAnggota() {
        const form = document.getElementById('anggotaForm');
        const formData = new FormData(form);
        const id = document.getElementById('anggotaId').value;

        const url = id ? `/admin/anggota/${id}` : '/admin/anggota';
        const method = id ? 'POST' : 'POST';
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        data['_method'] = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    }

    function toggleStatus(id) {
        fetch(`/admin/anggota/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }

    function deleteAnggota(id) {
        if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
            fetch(`/admin/anggota/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus anggota');
            });
        }
    }

    function filterAnggota(event) {
        // if Enter pressed, submit to server for full search
        if (event && event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('searchForm').submit();
            return;
        }

        const searchTerm = document.getElementById('search').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter') ? document.getElementById('statusFilter').value : '';
        const rows = document.querySelectorAll('#anggotaTable tr');

        rows.forEach(row => {
            const nama = row.cells[1].textContent.toLowerCase();
            const nis = row.cells[2].textContent.toLowerCase();
            const username = row.cells[3].textContent.toLowerCase();
            const status = row.cells[7].textContent.toLowerCase();

            let matchesSearch = false;
            if (!searchTerm) {
                matchesSearch = true;
            } else if (/^\d+$/.test(searchTerm)) {
                // numeric -> only search NIS
                matchesSearch = nis.includes(searchTerm);
            } else if (!searchTerm.includes(' ')) {
                // single token (no spaces) -> search username
                matchesSearch = username.includes(searchTerm);
            } else {
                // contains spaces -> likely a full name
                matchesSearch = nama.includes(searchTerm);
            }

            const matchesStatus = statusFilter === '' || status.includes(statusFilter);

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // CSRF token is already available in meta tag

        // Add event listeners for buttons
        document.querySelectorAll('.edit-anggota').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditModal(id);
            });
        });

        document.querySelectorAll('.toggle-status').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                toggleStatus(id);
            });
        });

        document.querySelectorAll('.delete-anggota').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                deleteAnggota(id);
            });
        });
    });
</script>
@endpush