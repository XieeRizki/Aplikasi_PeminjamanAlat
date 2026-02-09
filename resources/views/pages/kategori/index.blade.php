@extends('layouts.app')

@section('title', 'Kategori Alat')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Kategori Alat</h2>
        <button onclick="openModal()" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded text-xs font-semibold flex items-center space-x-1.5 transition-all duration-200 shadow">
            <i class="fas fa-plus text-sm"></i>
            <span>Tambah Kategori</span>
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

    <!-- Card Grid -->
    @if($kategori && count($kategori) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kategori as $item)
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $item['nama_kategori'] ?? $item->nama_kategori }}</h3>
                    <p class="text-slate-600 text-sm mb-4">{{ $item['deskripsi'] ?? $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    <div class="flex space-x-3 text-sm">
                        <button type="button" onclick="editModal({{ $item['id'] ?? $item->kategori_id }}, '{{ $item['nama_kategori'] ?? $item->nama_kategori }}', '{{ $item['deskripsi'] ?? $item->deskripsi }}')" class="text-blue-600 hover:text-blue-900 font-medium">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form action="{{ route('kategori.destroy', $item['id'] ?? $item->kategori_id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-folder-open text-6xl text-slate-300 mb-4"></i>
            <p class="text-slate-500 text-lg mb-2">Belum ada kategori</p>
            <p class="text-slate-400 text-sm">Klik tombol "Tambah Kategori" untuk menambahkan kategori baru.</p>
        </div>
    @endif

    <!-- Modal Tambah/Edit Kategori -->
    <div id="kategoriModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded shadow-lg max-w-md w-full">
            <!-- Modal Header -->
            <div class="bg-slate-900 text-white px-4 py-3">
                <h2 class="text-sm font-bold flex items-center">
                    <i class="fas fa-folder mr-2 text-base"></i>
                    <span id="modalTitle">Tambah Kategori</span>
                </h2>
            </div>

            <!-- Modal Body -->
            <form id="kategoriForm" method="POST" action="{{ route('kategori.store') }}" class="p-4 space-y-3">
                @csrf
                <div id="methodField"></div>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Nama Kategori</label>
                    <input type="text" id="namaKategori" name="nama_kategori" required 
                        class="w-full px-3 py-1.5 border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1.5">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
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
            document.getElementById('modalTitle').textContent = 'Tambah Kategori';
            document.getElementById('namaKategori').value = '';
            document.getElementById('deskripsi').value = '';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('kategoriForm').action = '{{ route("kategori.store") }}';
            document.getElementById('kategoriModal').classList.remove('hidden');
        }

        function editModal(id, nama, deskripsi) {
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('namaKategori').value = nama;
            document.getElementById('deskripsi').value = deskripsi;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('kategoriForm').action = `{{ url('/kategori') }}/${id}`;
            document.getElementById('kategoriModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('kategoriModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection