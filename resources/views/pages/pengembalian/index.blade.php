@extends('layouts.app')

@section('title', 'Monitoring Pengembalian')

@section('content')
    <!-- Header Section -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Monitoring Pengembalian</h1>
            <p class="text-slate-600 text-sm mt-0.5">Pantau status pengembalian alat</p>
        </div>
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-800">
            <p class="text-slate-600 text-xs font-medium uppercase">Masih Dipinjam</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ collect($peminjamanans ?? [])->where('status', 'disetujui')->count() }}</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Sudah Dikembalikan</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">0</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Terlambat</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">0</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Total Denda</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">Rp 0</p>
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
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Tgl Pinjam</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Jatuh Tempo</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Status</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($peminjamanans ?? [] as $index => $peminjaman)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-4 py-2.5 text-xs text-slate-700 font-medium">{{ $index + 1 }}</td>
                            <td class="px-4 py-2.5 text-xs font-semibold text-slate-900">
                                {{ $peminjaman->alat->nama_alat ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ $peminjaman->user->username ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ $peminjaman->tanggal_peminjaman->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-700">
                                {{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-hourglass-half text-xs"></i>
                                    <span>Dipinjam</span>
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <button onclick="openModal({{ $peminjaman->peminjaman_id }})" 
                                    class="bg-slate-700 hover:bg-slate-800 text-white px-2.5 py-1 rounded text-xs font-medium transition-all inline-flex items-center space-x-1">
                                    <i class="fas fa-check text-xs"></i>
                                    <span>Kembali</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-check-circle text-slate-300 text-3xl"></i>
                                    <p class="text-slate-500 font-medium text-sm mt-2">Tidak ada peminjaman yang menunggu pengembalian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Pengembalian -->
    <div id="pengembalianModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded shadow-lg max-w-md w-full">
            <!-- Modal Header -->
            <div class="bg-slate-900 text-white px-4 py-3">
                <h2 class="text-sm font-bold flex items-center">
                    <i class="fas fa-check-circle mr-2 text-base"></i>
                    Konfirmasi Pengembalian
                </h2>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('pengembalian.store') }}" class="p-4 space-y-3">
                @csrf
                
                <input type="hidden" id="peminjamanId" name="peminjaman_id">

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali_aktual" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Kondisi Alat</label>
                    <select name="kondisi_alat" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent text-sm">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3" 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent text-sm resize-none"
                        placeholder="Tulis keterangan pengembalian..."></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="flex space-x-2 pt-3 border-t border-slate-200">
                    <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white py-1.5 rounded text-xs font-semibold transition-all">
                        <i class="fas fa-save mr-1"></i>Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 py-1.5 rounded text-xs font-semibold transition-all">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(peminjamanId) {
            document.getElementById('peminjamanId').value = peminjamanId;
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="tanggal_kembali_aktual"]').value = today;
            document.getElementById('pengembalianModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('pengembalianModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('pengembalianModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection