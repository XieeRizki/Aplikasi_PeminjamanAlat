@extends('layouts.app')

@section('title', 'Monitoring Pengembalian')

@section('content')
    <!-- Header Section -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Monitoring Pengembalian</h1>
            <p class="text-slate-600 text-sm mt-0.5">Pantau status pengembalian alat</p>
        </div>
        <button onclick="openModal()" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded text-xs font-semibold flex items-center space-x-1.5 transition-all duration-200 shadow">
            <i class="fas fa-plus text-sm"></i>
            <span>Catat Pengembalian</span>
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-800">
            <p class="text-slate-600 text-xs font-medium uppercase">Dipinjam</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">2</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Sudah Dikembalikan</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">1</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Terlambat</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">0</p>
        </div>
        <div class="bg-white rounded shadow p-3 border-l-2 border-slate-700">
            <p class="text-slate-600 text-xs font-medium uppercase">Hilang</p>
            <p class="text-xl font-bold text-slate-900 mt-0.5">0</p>
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
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Tgl Kembali</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Status</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-4 py-2.5 text-xs text-slate-700 font-medium">1</td>
                        <td class="px-4 py-2.5 text-xs font-semibold text-slate-900">Laptop Dell</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">Budi Santoso</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">28 Jan 2026</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">01 Feb 2026</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">-</td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs font-medium bg-slate-200 text-slate-800">
                                <i class="fas fa-hourglass-half text-xs"></i>
                                <span>Dipinjam</span>
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <div class="flex justify-center space-x-1.5">
                                <button onclick="konfirmasiKembali()" class="bg-slate-700 hover:bg-slate-800 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                    <i class="fas fa-check text-xs"></i>
                                    <span>Kembali</span>
                                </button>
                                <button onclick="lihatDetail()" class="bg-slate-600 hover:bg-slate-700 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-4 py-2.5 text-xs text-slate-700 font-medium">2</td>
                        <td class="px-4 py-2.5 text-xs font-semibold text-slate-900">Proyektor</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">Ani Wijaya</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">30 Jan 2026</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">02 Feb 2026</td>
                        <td class="px-4 py-2.5 text-xs text-slate-700">02 Feb 2026</td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs font-medium bg-slate-200 text-slate-800">
                                <i class="fas fa-check-circle text-xs"></i>
                                <span>Dikembalikan</span>
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <button onclick="lihatDetail()" class="bg-slate-600 hover:bg-slate-700 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Pengembalian -->
    <div id="pengembalianModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded shadow-lg max-w-sm w-full">
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
                
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Alat</label>
                    <input type="text" value="Laptop Dell" disabled
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none text-sm bg-slate-100">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Peminjam</label>
                    <input type="text" value="Budi Santoso" disabled
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none text-sm bg-slate-100">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Tanggal Pengembalian</label>
                    <input type="date" name="tgl_kembali" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Kondisi Alat</label>
                    <select name="kondisi_kembali" required class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm resize-none"
                        placeholder="Tulis keterangan..."></textarea>
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
            document.getElementById('pengembalianModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('pengembalianModal').classList.add('hidden');
        }

        function konfirmasiKembali() {
            openModal();
        }

        function lihatDetail() {
            alert('Detail pengembalian');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('pengembalianModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection