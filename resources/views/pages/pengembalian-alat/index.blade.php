@extends('layouts.app')

@section('title', 'Kembalikan Alat')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Kembalikan Alat</h1>
                <p class="text-slate-600 text-sm mt-1">Daftar alat yang siap dikembalikan</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Peminjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Kembali Rencana</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($peminjamans as $index => $peminjaman)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                            {{ $peminjaman->alat->nama_alat ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $peminjaman->jumlah }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ ucfirst($peminjaman->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition-all duration-200" onclick="openReturnModal({{ $peminjaman->peminjaman_id }}, '{{ $peminjaman->alat->nama_alat }}')">
                                <i class="fas fa-check"></i> Kembalikan
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            Tidak ada alat yang siap dikembalikan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $peminjamans->links() }}
        </div>
    </div>

    <!-- Return Modal -->
    <div id="returnModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900">Kembalikan Alat</h2>
            </div>

            <!-- Modal Body -->
            <form id="returnForm" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Alat</label>
                    <input type="text" id="alatName" readonly class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-100" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Tanggal Pengembalian *</label>
                    <input type="date" name="tanggal_kembali_aktual" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Kondisi Alat *</label>
                    <select name="kondisi_alat" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Masukkan keterangan jika ada..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900"></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="flex space-x-2 pt-4 border-t border-slate-200">
                    <button type="button" onclick="closeReturnModal()" class="flex-1 px-4 py-2 border border-slate-300 text-slate-900 rounded-lg hover:bg-slate-100 font-semibold transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-semibold transition-all">Kembalikan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReturnModal(peminjamanId, alatName) {
            document.getElementById('alatName').value = alatName;
            document.getElementById('returnForm').action = `/pengembalian-alat/${peminjamanId}`;
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('returnModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReturnModal();
            }
        });
    </script>
@endsection