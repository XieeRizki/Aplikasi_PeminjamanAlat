@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <!-- Header Section -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Peminjaman</h1>
            <p class="text-slate-600 text-sm mt-0.5">Kelola peminjaman alat dengan mudah</p>
        </div>
        <button onclick="openModal()" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded text-xs font-semibold flex items-center space-x-1.5 transition-all duration-200 shadow">
            <i class="fas fa-plus text-sm"></i>
            <span>Ajukan Peminjaman</span>
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-white border-l-2 border-slate-800 text-slate-900 px-4 py-3 rounded mb-4 flex justify-between items-center shadow text-sm">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle text-slate-800 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-600 hover:text-slate-900">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-800">
            <p class="text-slate-600 text-xs font-medium uppercase">Peminjaman Aktif</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ count($peminjamanans ?? []) }}</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Menunggu Pengembalian</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">2</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Terlambat</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">0</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded shadow overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Alat</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Peminjam</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Jumlah</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Tgl Pinjam</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Jatuh Tempo</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Status</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($peminjamanans ?? [] as $peminjaman)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-4 py-2.5 text-xs font-semibold text-slate-900">{{ $peminjaman['alat'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">{{ $peminjaman['peminjam'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">{{ $peminjaman['jumlah'] ?? 0 }}</td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">{{ $peminjaman['tgl_pinjam'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2.5 text-xs text-slate-700 font-medium">{{ $peminjaman['jatuh_tempo'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2.5">
                                @php
                                    $status = $peminjaman['status'] ?? 'aktif';
                                    $icon = match($status) {
                                        'aktif' => 'fa-hourglass-half',
                                        'selesai' => 'fa-check-circle',
                                        'terlambat' => 'fa-exclamation-circle',
                                        default => 'fa-question-circle'
                                    };
                                @endphp
                                <span class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs font-medium bg-slate-200 text-slate-800">
                                    <i class="fas {{ $icon }} text-xs"></i>
                                    <span>{{ ucfirst($status) }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex justify-center space-x-1.5">
                                    <button onclick="editPeminjaman()" class="bg-slate-700 hover:bg-slate-800 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                        <i class="fas fa-edit text-xs"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button onclick="deletePeminjaman()" class="bg-slate-600 hover:bg-slate-700 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                        <i class="fas fa-trash text-xs"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fas fa-clipboard-list text-slate-300 text-3xl"></i>
                                    <p class="text-slate-500 font-medium text-xs">Belum ada data peminjaman</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajukan Peminjaman -->
    <div id="peminjamanModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded shadow-lg max-w-sm w-full">
            <!-- Modal Header -->
            <div class="bg-slate-900 text-white px-4 py-3">
                <h2 class="text-sm font-bold flex items-center">
                    <i class="fas fa-clipboard-list mr-2 text-base"></i>
                    Ajukan Peminjaman
                </h2>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('peminjaman.store') }}" class="p-4 space-y-3">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Alat</label>
                    <select name="alat_id" required class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                        <option value="">Pilih Alat</option>
                        <option value="1">Laptop</option>
                        <option value="2">Proyektor</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Peminjam</label>
                    <input type="text" name="peminjam" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Jumlah</label>
                    <input type="number" name="jumlah" required min="1"
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <!-- Modal Footer -->
                <div class="flex space-x-2 pt-3 border-t border-slate-200">
                    <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white py-1.5 rounded text-xs font-semibold transition-all duration-200">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 py-1.5 rounded text-xs font-semibold transition-all duration-200">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('peminjamanModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('peminjamanModal').classList.add('hidden');
        }

        function editPeminjaman() {
            openModal();
        }

        function deletePeminjaman() {
            if (confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')) {
                // Implement delete functionality
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('peminjamanModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection