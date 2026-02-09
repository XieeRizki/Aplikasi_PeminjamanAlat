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

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-white border-l-2 border-red-600 text-red-600 px-4 py-3 rounded mb-4 flex justify-between items-center shadow text-sm">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-800">
            <p class="text-slate-600 text-xs font-medium uppercase">Peminjaman Aktif</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ count($peminjamans ?? []) }}</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Menunggu Persetujuan</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ collect($peminjamans ?? [])->where('status', 'menunggu')->count() }}</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Sudah Disetujui</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ collect($peminjamans ?? [])->where('status', 'disetujui')->count() }}</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded shadow overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">No</th>
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
                    @forelse($peminjamans ?? [] as $index => $peminjaman)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-4 py-2.5 text-xs text-slate-700 font-medium">{{ $index + 1 }}</td>
                            <td class="px-4 py-2.5 text-xs text-slate-900">
                                {{ $peminjaman['alat']['nama_alat'] ?? $peminjaman->alat->nama_alat ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ $peminjaman['user']['username'] ?? $peminjaman->user->username ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ $peminjaman['jumlah'] ?? $peminjaman->jumlah }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ isset($peminjaman['tanggal_peminjaman']) ? date('d/m/Y', strtotime($peminjaman['tanggal_peminjaman'])) : $peminjaman->tanggal_peminjaman->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ isset($peminjaman['tanggal_kembali_rencana']) ? date('d/m/Y', strtotime($peminjaman['tanggal_kembali_rencana'])) : $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2.5 text-xs">
                                @php
                                    $status = $peminjaman['status'] ?? $peminjaman->status;
                                    $statusClass = match($status) {
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-blue-100 text-blue-800',
                                        'dikembalikan' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                    $statusText = match($status) {
                                        'menunggu' => 'Menunggu',
                                        'disetujui' => 'Disetujui',
                                        'dikembalikan' => 'Dikembalikan',
                                        'ditolak' => 'Ditolak',
                                        default => $status
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full font-semibold {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center text-xs space-x-1 flex justify-center">
                                @if(($peminjaman['status'] ?? $peminjaman->status) === 'menunggu')
                                    <form action="{{ route('peminjaman.approve', $peminjaman['peminjaman_id'] ?? $peminjaman->peminjaman_id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs font-semibold transition-all">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('peminjaman.reject', $peminjaman['peminjaman_id'] ?? $peminjaman->peminjaman_id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs font-semibold transition-all" onclick="return confirm('Tolak peminjaman ini?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-clipboard-list text-slate-300 text-3xl"></i>
                                    <p class="text-slate-500 font-medium text-xs mt-2">Belum ada data peminjaman</p>
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
                    Ajukan Peminjaman Baru
                </h2>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('peminjaman.store') }}" class="p-4 space-y-3">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Alat</label>
                    <select name="alat_id" id="alatSelect" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                        <option value="">-- Pilih Alat --</option>
                        @foreach($alats ?? [] as $alat)
                            <option value="{{ $alat['alat_id'] ?? $alat->alat_id }}">
                                {{ $alat['nama_alat'] ?? $alat->nama_alat }} (Stok: {{ $alat['stok_tersedia'] ?? $alat->stok_tersedia }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" required min="1"
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_peminjaman" id="tglPinjam" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Jatuh Tempo</label>
                    <input type="date" name="tanggal_kembali_rencana" id="jatuhTempo" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Tujuan Peminjaman</label>
                    <textarea name="tujuan_peminjaman" rows="2"
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm"></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="flex space-x-2 pt-3 border-t border-slate-200">
                    <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white py-1.5 rounded text-xs font-semibold transition-all duration-200">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 py-1.5 rounded text-xs font-semibold transition-all duration-200">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tglPinjam').value = today;
            document.getElementById('tglPinjam').setAttribute('min', today);
            document.getElementById('jatuhTempo').setAttribute('min', today);
            document.getElementById('peminjamanModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('peminjamanModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('peminjamanModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection