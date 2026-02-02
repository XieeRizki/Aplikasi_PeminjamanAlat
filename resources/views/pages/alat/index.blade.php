@extends('layouts.app')

@section('title', 'Manajemen Alat')

@section('content')
    <!-- Header Section -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Alat</h1>
            <p class="text-slate-600 text-sm mt-0.5">Kelola daftar alat yang tersedia</p>
        </div>
        <button onclick="openModal()" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded text-xs font-semibold flex items-center space-x-1.5 transition-all duration-200 shadow">
            <i class="fas fa-plus text-sm"></i>
            <span>Tambah Alat</span>
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
        <div class="bg-white border-l-2 border-slate-800 text-slate-900 px-4 py-3 rounded mb-4 flex justify-between items-center shadow text-sm">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle text-slate-800 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-600 hover:text-slate-900">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white rounded shadow overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">No</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Nama Alat</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Kategori</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Stok</th>
                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase tracking-widest">Kondisi</th>
                        <th class="px-4 py-2.5 text-center text-xs font-bold uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($alats ?? [] as $index => $alat)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-4 py-2.5 text-xs text-slate-700 font-medium">{{ $index + 1 }}</td>
                            <td class="px-4 py-2.5">
                                <span class="text-slate-900 font-semibold text-xs">{{ $alat['nama'] ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-200 text-slate-800">
                                    {{ $alat['kategori'] ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-xs font-semibold text-slate-900">{{ $alat['stok'] ?? 0 }} Unit</td>
                            <td class="px-4 py-2.5">
                                @php
                                    $kondisi = $alat['kondisi'] ?? 'baik';
                                    $icon = $kondisi === 'baik' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                                @endphp
                                <span class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs font-medium bg-slate-200 text-slate-800">
                                    <i class="fas {{ $icon }} text-xs"></i>
                                    <span>{{ ucfirst($kondisi) }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex justify-center space-x-1.5">
                                    <button onclick="editAlat('{{ $alat['id'] ?? '' }}')" class="bg-slate-700 hover:bg-slate-800 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                        <i class="fas fa-edit text-xs"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button onclick="deleteAlat('{{ $alat['id'] ?? '' }}')" class="bg-slate-600 hover:bg-slate-700 text-white px-2.5 py-1 rounded text-xs font-medium transition-all duration-200 inline-flex items-center space-x-1">
                                        <i class="fas fa-trash text-xs"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fas fa-inbox text-slate-300 text-3xl"></i>
                                    <p class="text-slate-500 font-medium text-xs">Belum ada data alat</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit Alat -->
    <div id="alatModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded shadow-lg max-w-sm w-full">
            <!-- Modal Header -->
            <div class="bg-slate-900 text-white px-4 py-3">
                <h2 class="text-sm font-bold flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-base"></i>
                    Tambah Alat
                </h2>
            </div>

            <!-- Modal Body -->
            <form id="alatForm" method="POST" action="{{ route('alat.store') }}" class="p-4 space-y-3">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Nama Alat</label>
                    <input type="text" name="nama" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Kategori</label>
                    <select name="kategori" required class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                        <option value="">Pilih Kategori</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Perkakas">Perkakas</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Stok</label>
                    <input type="number" name="stok" required min="1"
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Kondisi</label>
                    <select name="kondisi" required class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
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
            document.getElementById('alatModal').classList.remove('hidden');
            document.getElementById('alatForm').reset();
        }

        function closeModal() {
            document.getElementById('alatModal').classList.add('hidden');
        }

        function editAlat(id) {
            openModal();
        }

        function deleteAlat(id) {
            if (confirm('Apakah Anda yakin ingin menghapus alat ini?')) {
                // Implement delete functionality
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('alatModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection