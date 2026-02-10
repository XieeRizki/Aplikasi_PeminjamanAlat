<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;

// ============ AUTH ROUTES (PUBLIC) ============

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ============ PROTECTED ROUTES (ALL AUTHENTICATED USERS) ============

Route::middleware('auth')->group(function () {
    // LOGOUT - SEMUA ROLE
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // DASHBOARD - SEMUA ROLE
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Customize stats based on role
        if ($user->level === 'peminjam') {
            $stats = [
                'total_alat' => Alat::where('kondisi', 'baik')->count(),
                'my_peminjaman' => Peminjaman::where('user_id', $user->id)->count(),
                'my_pending' => Peminjaman::where('user_id', $user->id)->where('status', 'menunggu')->count(),
            ];
        } else {
            $stats = [
                'total_alat' => Alat::count(),
                'total_peminjaman' => Peminjaman::count(),
                'peminjaman_pending' => Peminjaman::where('status', 'menunggu')->count(),
                'total_pengembalian' => Pengembalian::count(),
            ];
        }
        
        $recentActivities = LogAktivitas::with('user')->latest('timestamp')->limit(5)->get();
        return view('pages.dashboard', compact('stats', 'recentActivities', 'user'));
    })->name('dashboard');

    // ========== ADMIN ONLY ROUTES ==========
    Route::middleware('role:admin')->group(function () {

        // ===== USERS MANAGEMENT =====
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // ===== ALAT MANAGEMENT (ADMIN FULL CRUD) =====
        Route::get('/admin/alat', function () {
            $alats = Alat::with('kategori')->get();
            $kategoris = Kategori::all();
            $totalAlat = $alats->count();
            $totalTersedia = $alats->sum('stok_tersedia');
            $totalKategori = $kategoris->count();
            $alatRusak = $alats->where('kondisi', 'rusak')->count();
            
            return view('pages.alat.index', compact('alats', 'kategoris', 'totalAlat', 'totalTersedia', 'totalKategori', 'alatRusak'));
        })->name('alat.index');

        Route::post('/admin/alat', function (Request $request) {
            $request->validate([
                'nama_alat' => 'required|string',
                'kategori_id' => 'required|exists:kategori,kategori_id',
                'stok_total' => 'required|integer|min:1',
                'stok_tersedia' => 'required|integer|min:0',
                'kondisi' => 'required|in:baik,rusak',
            ]);

            Alat::create([
                'nama_alat' => $request->nama_alat,
                'kategori_id' => $request->kategori_id,
                'stok_total' => $request->stok_total,
                'stok_tersedia' => $request->stok_tersedia,
                'kondisi' => $request->kondisi,
            ]);

            LogAktivitas::createLog(auth()->id(), "Menambah alat: {$request->nama_alat}", 'Alat');
            return back()->with('success', 'Alat berhasil ditambahkan!');
        })->name('alat.store');

        Route::put('/admin/alat/{alat}', function (Request $request, Alat $alat) {
            $request->validate([
                'nama_alat' => 'required|string',
                'kategori_id' => 'required|exists:kategori,kategori_id',
                'stok_total' => 'required|integer|min:1',
                'stok_tersedia' => 'required|integer|min:0',
                'kondisi' => 'required|in:baik,rusak',
            ]);

            $alat->update($request->all());
            LogAktivitas::createLog(auth()->id(), "Mengubah alat: {$alat->nama_alat}", 'Alat');
            return back()->with('success', 'Alat berhasil diubah!');
        })->name('alat.update');

        Route::delete('/admin/alat/{alat}', function (Alat $alat) {
            $alat->delete();
            LogAktivitas::createLog(auth()->id(), "Menghapus alat: {$alat->nama_alat}", 'Alat');
            return back()->with('success', 'Alat berhasil dihapus!');
        })->name('alat.destroy');

        // ===== KATEGORI MANAGEMENT =====
        Route::get('/kategori', function () {
            $kategori = Kategori::all();
            return view('pages.kategori.index', compact('kategori'));
        })->name('kategori.index');

        Route::post('/kategori', function (Request $request) {
            $request->validate([
                'nama_kategori' => 'required|string|unique:kategori,nama_kategori',
                'deskripsi' => 'nullable|string',
            ]);

            Kategori::create($request->only('nama_kategori', 'deskripsi'));
            LogAktivitas::createLog(auth()->id(), "Menambah kategori: {$request->nama_kategori}", 'Kategori');
            return back()->with('success', 'Kategori berhasil ditambahkan!');
        })->name('kategori.store');

        Route::put('/kategori/{kategori}', function (Request $request, Kategori $kategori) {
            $request->validate([
                'nama_kategori' => 'required|string|unique:kategori,nama_kategori,' . $kategori->kategori_id . ',kategori_id',
                'deskripsi' => 'nullable|string',
            ]);

            $kategori->update($request->only('nama_kategori', 'deskripsi'));
            LogAktivitas::createLog(auth()->id(), "Mengubah kategori: {$kategori->nama_kategori}", 'Kategori');
            return back()->with('success', 'Kategori berhasil diubah!');
        })->name('kategori.update');

        Route::delete('/kategori/{kategori}', function (Kategori $kategori) {
            $kategori->delete();
            LogAktivitas::createLog(auth()->id(), "Menghapus kategori: {$kategori->nama_kategori}", 'Kategori');
            return back()->with('success', 'Kategori berhasil dihapus!');
        })->name('kategori.destroy');

        // ===== PEMINJAMAN MANAGEMENT (ADMIN) =====
        Route::get('/peminjaman', function () {
            $alats = Alat::all();
            $peminjamans = Peminjaman::with('user', 'alat')->latest('tanggal_peminjaman')->get();
            return view('pages.peminjaman.index', compact('peminjamans', 'alats'));
        })->name('peminjaman.index');

        Route::post('/peminjaman', function (Request $request) {
            $request->validate([
                'alat_id' => 'required|exists:alat,alat_id',
                'jumlah' => 'required|integer|min:1',
                'tanggal_peminjaman' => 'required|date',
                'tanggal_kembali_rencana' => 'required|date|after:tanggal_peminjaman',
                'tujuan_peminjaman' => 'nullable|string',
            ]);

            $alat = Alat::findOrFail($request->alat_id);

            if ($request->jumlah > $alat->stok_tersedia) {
                return back()->with('error', "Stok tidak cukup! Tersedia: {$alat->stok_tersedia}");
            }

            Peminjaman::create([
                'user_id' => auth()->id(),
                'alat_id' => $alat->alat_id,
                'jumlah' => $request->jumlah,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'status' => 'disetujui',
            ]);

            LogAktivitas::createLog(auth()->id(), "Membuat peminjaman: {$alat->nama_alat}", 'Peminjaman');
            return back()->with('success', 'Peminjaman berhasil dibuat!');
        })->name('peminjaman.store');

        Route::put('/peminjaman/{peminjaman}', function (Request $request, Peminjaman $peminjaman) {
            $request->validate([
                'jumlah' => 'required|integer|min:1',
                'tanggal_kembali_rencana' => 'required|date|after:tanggal_peminjaman',
                'tujuan_peminjaman' => 'nullable|string',
                'status' => 'required|in:menunggu,disetujui,ditolak,selesai',
            ]);

            $peminjaman->update($request->all());
            LogAktivitas::createLog(auth()->id(), "Mengubah peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');
            return back()->with('success', 'Peminjaman berhasil diubah!');
        })->name('peminjaman.update');

        Route::delete('/peminjaman/{peminjaman}', function (Peminjaman $peminjaman) {
            $peminjaman->delete();
            LogAktivitas::createLog(auth()->id(), "Menghapus peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');
            return back()->with('success', 'Peminjaman berhasil dihapus!');
        })->name('peminjaman.destroy');

        // ===== PENGEMBALIAN MANAGEMENT =====
        Route::get('/pengembalian', function () {
            $pengembalians = Pengembalian::with('peminjaman', 'peminjaman.user', 'peminjaman.alat')
                ->latest('tanggal_kembali_aktual')
                ->get();
            return view('pages.pengembalian.index', compact('pengembalians'));
        })->name('pengembalian.index');

        Route::post('/pengembalian', function (Request $request) {
            $request->validate([
                'peminjaman_id' => 'required|exists:peminjaman,peminjaman_id',
                'tanggal_kembali_aktual' => 'required|date',
                'kondisi_alat' => 'required|in:baik,rusak',
                'keterangan' => 'nullable|string',
            ]);

            Pengembalian::create($request->all());
            LogAktivitas::createLog(auth()->id(), "Membuat pengembalian", 'Pengembalian');
            return back()->with('success', 'Pengembalian berhasil dicatat!');
        })->name('pengembalian.store');

        Route::delete('/pengembalian/{pengembalian}', function (Pengembalian $pengembalian) {
            $pengembalian->delete();
            LogAktivitas::createLog(auth()->id(), "Menghapus pengembalian", 'Pengembalian');
            return back()->with('success', 'Pengembalian berhasil dihapus!');
        })->name('pengembalian.destroy');

        // ===== LOG AKTIVITAS =====
        Route::get('/log', function () {
            $logs = LogAktivitas::with('user')->latest('timestamp')->paginate(20);
            return view('pages.log.index', compact('logs'));
        })->name('log.index');
    });

    // ========== PETUGAS ONLY ROUTES ==========
    Route::middleware('role:petugas')->group(function () {

        // ===== PERSETUJUAN PEMINJAMAN =====
        Route::get('/persetujuan', function () {
            $peminjamans = Peminjaman::with('user', 'alat')
                ->where('status', 'menunggu')
                ->latest('tanggal_peminjaman')
                ->paginate(10);
            return view('pages.persetujuan.index', compact('peminjamans'));
        })->name('persetujuan.index');

        Route::post('/persetujuan/{peminjaman}/approve', function (Peminjaman $peminjaman) {
            if ($peminjaman->status !== 'menunggu') {
                return back()->with('error', 'Status peminjaman tidak valid!');
            }

            $peminjaman->update(['status' => 'disetujui']);
            LogAktivitas::createLog(auth()->id(), "Menyetujui peminjaman ID: {$peminjaman->peminjaman_id}", 'Persetujuan');
            return back()->with('success', 'Peminjaman berhasil disetujui!');
        })->name('persetujuan.approve');

        Route::post('/persetujuan/{peminjaman}/reject', function (Peminjaman $peminjaman) {
            if ($peminjaman->status !== 'menunggu') {
                return back()->with('error', 'Status peminjaman tidak valid!');
            }

            $peminjaman->update(['status' => 'ditolak']);
            LogAktivitas::createLog(auth()->id(), "Menolak peminjaman ID: {$peminjaman->peminjaman_id}", 'Persetujuan');
            return back()->with('success', 'Peminjaman berhasil ditolak!');
        })->name('persetujuan.reject');

        // ===== MONITORING PENGEMBALIAN =====
        Route::get('/monitoring-pengembalian', function () {
            $pengembalians = Pengembalian::with('peminjaman', 'peminjaman.user', 'peminjaman.alat')
                ->latest('tanggal_kembali_aktual')
                ->paginate(10);
            return view('pages.monitoring-pengembalian.index', compact('pengembalians'));
        })->name('monitoring-pengembalian.index');

        // ===== LAPORAN =====
        Route::get('/laporan', function () {
            $peminjamans = Peminjaman::with('user', 'alat')->latest('tanggal_peminjaman')->get();
            return view('pages.laporan.index', compact('peminjamans'));
        })->name('laporan.index');
    });

    // ========== PEMINJAM ONLY ROUTES ==========
    Route::middleware('role:peminjam')->group(function () {

        // ===== LIHAT DAFTAR ALAT (READ ONLY) =====
        Route::get('/alat', function () {
            $alats = Alat::with('kategori')->where('kondisi', 'baik')->get();
            $kategoris = Kategori::all();
            $totalAlat = Alat::count();
            $totalTersedia = $alats->sum('stok_tersedia');
            $totalKategori = $kategoris->count();
            $alatRusak = Alat::where('kondisi', 'rusak')->count();
            
            return view('pages.alat.index', compact('alats', 'kategoris', 'totalAlat', 'totalTersedia', 'totalKategori', 'alatRusak'));
        })->name('alat.index');

        // ===== MENGAJUKAN PEMINJAMAN =====
        Route::post('/ajukan-peminjaman', function (Request $request) {
            $request->validate([
                'alat_id' => 'required|exists:alat,alat_id',
                'jumlah' => 'required|integer|min:1',
                'tanggal_kembali_rencana' => 'required|date|after:today',
                'tujuan_peminjaman' => 'nullable|string',
            ]);

            $alat = Alat::findOrFail($request->alat_id);

            if ($request->jumlah > $alat->stok_tersedia) {
                return back()->with('error', "Stok tidak cukup! Tersedia: {$alat->stok_tersedia}");
            }

            Peminjaman::create([
                'user_id' => auth()->id(),
                'alat_id' => $alat->alat_id,
                'jumlah' => $request->jumlah,
                'tanggal_peminjaman' => date('Y-m-d'),
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'status' => 'menunggu',
            ]);

            LogAktivitas::createLog(auth()->id(), "Mengajukan peminjaman: {$alat->nama_alat}", 'Peminjaman');
            return back()->with('success', 'Peminjaman berhasil diajukan!');
        })->name('ajukan-peminjaman.store');

        // ===== LIHAT RIWAYAT PEMINJAMAN =====
        Route::get('/riwayat-peminjaman', function () {
            $peminjamans = Peminjaman::where('user_id', auth()->id())
                ->with('alat')
                ->latest('tanggal_peminjaman')
                ->paginate(10);
            return view('pages.riwayat-peminjaman.index', compact('peminjamans'));
        })->name('riwayat-peminjaman.index');

        // ===== MENGEMBALIKAN ALAT =====
        Route::get('/pengembalian-alat', function () {
            $peminjamans = Peminjaman::where('user_id', auth()->id())
                ->where('status', 'disetujui')
                ->with('alat')
                ->latest('tanggal_peminjaman')
                ->paginate(10);
            return view('pages.pengembalian-alat.index', compact('peminjamans'));
        })->name('pengembalian-alat.index');

        Route::post('/pengembalian-alat/{peminjaman}', function (Request $request, Peminjaman $peminjaman) {
            // Verify peminjaman belongs to current user
            if ($peminjaman->user_id !== auth()->id()) {
                return back()->with('error', 'Unauthorized!');
            }

            $request->validate([
                'tanggal_kembali_aktual' => 'required|date',
                'kondisi_alat' => 'required|in:baik,rusak',
                'keterangan' => 'nullable|string',
            ]);

            Pengembalian::create([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'kondisi_alat' => $request->kondisi_alat,
                'keterangan' => $request->keterangan,
            ]);

            $peminjaman->update(['status' => 'selesai']);
            LogAktivitas::createLog(auth()->id(), "Mengembalikan alat", 'Pengembalian');
            return back()->with('success', 'Alat berhasil dikembalikan!');
        })->name('pengembalian-alat.store');
    });
});