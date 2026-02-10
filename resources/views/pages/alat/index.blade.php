@extends('layouts.app')

@section('title', 'Alat')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                @if(auth()->user()->level === 'peminjam')
                    <h1 class="text-3xl font-bold text-slate-900">Daftar Alat Tersedia</h1>
                    <p class="text-slate-600 text-sm mt-1">Jelajahi dan pinjam alat yang Anda butuhkan</p>
                @else
                    <h1 class="text-3xl font-bold text-slate-900">Kelola Alat</h1>
                    <p class="text-slate-600 text-sm mt-1">Manajemen inventaris alat dengan mudah</p>
                @endif
            </div>
            
            @if(auth()->user()->level !== 'peminjam')
                <button onclick="openAddModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Alat</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-lg mb-6 flex justify-between items-center shadow">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-lg mb-6 flex justify-between items-center shadow">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <!-- Total Alat -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-xs font-semibold uppercase tracking-wide">Total Alat</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalAlat }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 p-3 rounded-lg">
                    <i class="fas fa-box text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tersedia -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wide">Tersedia</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalTersedia }} / {{ $totalAlat }}</p>
                </div>
                <div class="bg-emerald-400 bg-opacity-30 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-purple-100 text-xs font-semibold uppercase tracking-wide">Kategori</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalKategori }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 p-3 rounded-lg">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Rusak -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-red-100 text-xs font-semibold uppercase tracking-wide">Rusak</p>
                    <p class="text-3xl font-bold mt-2">{{ $alatRusak }}</p>
                </div>
                <div class="bg-red-400 bg-opacity-30 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-800 mb-2">Cari Alat</label>
                <input type="text" id="searchAlat" placeholder="Nama atau kode alat..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-800 mb-2">Filter Kategori</label>
                <select id="filterKategori" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-800 mb-2">Filter Kondisi</label>
                <select id="filterKondisi" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                    <option value="">Semua Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Alat Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="alatGrid">
        @forelse($alats as $alat)
            <div class="bg-white rounded-lg shadow hover:shadow-xl transition-all duration-300 border border-slate-200 overflow-hidden alat-card"
                data-search="{{ strtolower($alat->nama_alat . ' ' . $alat->kode_alat) }}"
                data-kategori="{{ $alat->kategori_id }}"
                data-kondisi="{{ $alat->kondisi }}">
                
                <!-- Card Header with Status Badge -->
                <div class="relative bg-gradient-to-r {{ $alat->kondisi === 'baik' ? 'from-emerald-400 to-emerald-500' : ($alat->kondisi === 'rusak' ? 'from-red-400 to-red-500' : 'from-gray-400 to-gray-500') }} p-4 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold opacity-90">{{ $alat->kategori->nama_kategori }}</p>
                            <h3 class="text-lg font-bold mt-1 truncate">{{ $alat->nama_alat }}</h3>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-white {{ $alat->kondisi === 'baik' ? 'text-emerald-700' : ($alat->kondisi === 'rusak' ? 'text-red-700' : 'text-gray-700') }}">
                            {{ ucfirst($alat->kondisi) }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4">
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Kode</p>
                            <p class="text-sm font-mono text-slate-900">{{ $alat->kode_alat }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold mb-1">Stok</p>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-slate-200 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ ($alat->stok_tersedia / $alat->stok_total) * 100 }}%"></div>
                                </div>
                                <p class="text-xs font-semibold text-slate-900">{{ $alat->stok_tersedia }}/{{ $alat->stok_total }}</p>
                            </div>
                        </div>

                        @if($alat->lokasi)
                            <div>
                                <p class="text-xs text-slate-500 uppercase font-semibold">Lokasi</p>
                                <p class="text-xs text-slate-700">{{ $alat->lokasi }}</p>
                            </div>
                        @endif

                        @if($alat->deskripsi)
                            <div>
                                <p class="text-xs text-slate-600 line-clamp-2">{{ $alat->deskripsi }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 flex gap-2">
                    @if(auth()->user()->level === 'peminjam')
                        @if($alat->stok_tersedia > 0 && $alat->kondisi === 'baik')
                            <button onclick="pinjamAlat({{ $alat->alat_id }}, '{{ $alat->nama_alat }}')" 
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all flex items-center justify-center space-x-1">
                                <i class="fas fa-hand-holding text-sm"></i>
                                <span>Pinjam</span>
                            </button>
                        @else
                            <button disabled class="flex-1 bg-slate-300 text-slate-600 px-3 py-2 rounded-lg text-xs font-semibold flex items-center justify-center space-x-1 cursor-not-allowed">
                                <i class="fas fa-lock text-sm"></i>
                                <span>{{ $alat->stok_tersedia <= 0 ? 'Habis' : 'Rusak' }}</span>
                            </button>
                        @endif
                    @else
                        <button onclick="editAlat({{ $alat->alat_id }})" 
                            class="flex-1 bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all flex items-center justify-center space-x-1">
                            <i class="fas fa-edit text-sm"></i>
                            <span>Edit</span>
                        </button>
                        <form action="{{ route('alat.destroy', $alat->alat_id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus alat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all flex items-center justify-center space-x-1">
                                <i class="fas fa-trash text-sm"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-slate-50 border border-dashed border-slate-300 rounded-lg p-12 text-center">
                <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                <p class="text-slate-500 font-semibold">Belum ada alat</p>
            </div>
        @endempty
    </div>

    <!-- Modal Tambah/Edit Alat (Admin & Petugas Only) -->
    @if(auth()->user()->level !== 'peminjam')
        <div id="alatModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-lg w-full max-h-screen overflow-y-auto">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-4 sticky top-0 z-10">
                    <h2 class="text-lg font-bold flex items-center">
                        <i class="fas fa-plus-circle mr-3 text-xl"></i>
                        <span id="modalTitle">Tambah Alat Baru</span>
                    </h2>
                </div>

                <form id="alatForm" method="POST" action="{{ route('alat.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" id="alatMethod" name="_method" value="POST">
                    <input type="hidden" id="alatId">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Alat <span class="text-red-600">*</span></label>
                        <input type="text" name="nama_alat" id="namaAlat" required 
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Kategori <span class="text-red-600">*</span></label>
                            <select name="kategori_id" id="kategoriId" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->kategori_id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Kode Alat <span class="text-red-600">*</span></label>
                            <input type="text" name="kode_alat" id="kodeAlat" required 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm"
                                placeholder="ALT001">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Stok Total <span class="text-red-600">*</span></label>
                            <input type="number" name="stok_total" id="stokTotal" required min="1" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Kondisi <span class="text-red-600">*</span></label>
                            <select name="kondisi" id="kondisiAlat" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm">
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasiAlat"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm"
                            placeholder="Tempat penyimpanan">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsiAlat" rows="3"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm resize-none"
                            placeholder="Keterangan alat..."></textarea>
                    </div>

                    <div class="flex space-x-3 pt-4 border-t border-slate-200">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg text-sm font-semibold transition-all">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <button type="button" onclick="closeModal()" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 py-2 rounded-lg text-sm font-semibold transition-all">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Pinjam Alat (Peminjam Only) -->
    @if(auth()->user()->level === 'peminjam')
        <div id="pinjamModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-4">
                    <h2 class="text-lg font-bold flex items-center">
                        <i class="fas fa-hand-holding mr-3 text-xl"></i>
                        Pinjam Alat
                    </h2>
                </div>

                <form method="POST" action="{{ route('ajukan-peminjaman.store') }}" class="p-6 space-y-4">
                    @csrf
                    
                    <input type="hidden" name="alat_id" id="modalAlatId">

                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Alat</label>
                        <input type="text" id="modalAlatNama" disabled
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Jumlah <span class="text-red-600">*</span></label>
                        <input type="number" name="jumlah" required min="1" max="10" value="1"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal Kembali <span class="text-red-600">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">Tujuan Peminjaman</label>
                        <textarea name="tujuan_peminjaman" rows="3"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm resize-none"
                            placeholder="Jelaskan tujuan peminjaman..."></textarea>
                    </div>

                    <div class="flex space-x-3 pt-4 border-t border-slate-200">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg text-sm font-semibold transition-all">
                            <i class="fas fa-check mr-2"></i>Ajukan
                        </button>
                        <button type="button" onclick="closePinjamModal()" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 py-2 rounded-lg text-sm font-semibold transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        // Filter & Search
        function filterAlat() {
            const searchTerm = document.getElementById('searchAlat')?.value.toLowerCase() || '';
            const filterKategori = document.getElementById('filterKategori')?.value || '';
            const filterKondisi = document.getElementById('filterKondisi')?.value || '';
            const cards = document.querySelectorAll('.alat-card');

            cards.forEach(card => {
                let show = true;

                if (searchTerm && !card.dataset.search.includes(searchTerm)) {
                    show = false;
                }
                if (filterKategori && card.dataset.kategori !== filterKategori) {
                    show = false;
                }
                if (filterKondisi && card.dataset.kondisi !== filterKondisi) {
                    show = false;
                }

                card.style.display = show ? '' : 'none';
            });
        }

        document.getElementById('searchAlat')?.addEventListener('keyup', filterAlat);
        document.getElementById('filterKategori')?.addEventListener('change', filterAlat);
        document.getElementById('filterKondisi')?.addEventListener('change', filterAlat);

        // Modal Management
        function openAddModal() {
            document.getElementById('alatModal').classList.remove('hidden');
            document.getElementById('alatForm').reset();
            document.getElementById('modalTitle').textContent = 'Tambah Alat Baru';
            document.getElementById('alatMethod').value = 'POST';
            document.getElementById('alatForm').action = '{{ route("alat.store") }}';
        }

        function closeModal() {
            document.getElementById('alatModal').classList.add('hidden');
        }

        function editAlat(id) {
            // TODO: Implement edit - fetch alat data via AJAX
            openAddModal();
        }

        function pinjamAlat(alatId, alatNama) {
            document.getElementById('modalAlatId').value = alatId;
            document.getElementById('modalAlatNama').value = alatNama;
            document.getElementById('pinjamModal').classList.remove('hidden');
        }

        function closePinjamModal() {
            document.getElementById('pinjamModal').classList.add('hidden');
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const alatModal = document.getElementById('alatModal');
            const pinjamModal = document.getElementById('pinjamModal');
            
            if (event.target === alatModal) closeModal();
            if (event.target === pinjamModal) closePinjamModal();
        }
    </script>
@endsection