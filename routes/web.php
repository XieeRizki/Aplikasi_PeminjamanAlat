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

// ============ AUTH ROUTES ============

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ============ PROTECTED ROUTES ============

    // DASHBOARD
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $stats = [
            'total_alat' => Alat::count(),
            'total_peminjaman' => Peminjaman::count(),
            'peminjaman_pending' => Peminjaman::where('status', 'menunggu')->count(),
            'total_pengembalian' => Pengembalian::count(),
        ];
        $recentActivities = LogAktivitas::with('user')->latest('timestamp')->limit(5)->get();
        
        return view('pages.dashboard', compact('stats', 'recentActivities', 'user'));
    })->name('dashboard');

    // AJUKAN PEMINJAMAN (SEMUA)
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

    // RIWAYAT PEMINJAMAN (SEMUA)
    Route::get('/riwayat-peminjaman', function () {
        $peminjamans = Peminjaman::where('user_id', auth()->id())->with('alat')->latest('tanggal_peminjaman')->paginate(10);
        return view('pages.riwayat-peminjaman.index', compact('peminjamans'));
    })->name('riwayat-peminjaman.index');

    // ========== ADMIN & PETUGAS ROUTES (MANAGE ALAT) ==========
    Route::middleware(['auth', 'role:admin,petugas'])->group(function () {

        Route::get('/alat', function () {
            $alats = Alat::with('kategori')->get();
            $kategoris = Kategori::all();
            $totalAlat = $alats->count();
            $totalTersedia = $alats->sum('stok_tersedia');
            $totalKategori = $kategoris->count();
            $alatRusak = $alats->where('kondisi', 'rusak')->count();
            
            return view('pages.alat.index', compact('alats', 'kategoris', 'totalAlat', 'totalTersedia', 'totalKategori', 'alatRusak'));
        })->name('alat.index');

        Route::post('/alat', function (Request $request) {
            // ... create logic
        })->name('alat.store');

        Route::put('/alat/{alat}', function (Request $request, Alat $alat) {
            // ... update logic
        })->name('alat.update');

        Route::delete('/alat/{alat}', function (Alat $alat) {
            // ... delete logic
        })->name('alat.destroy');
    });

    // ========== PEMINJAM ROUTES (BROWSE ALAT ONLY) ==========
    Route::middleware(['auth', 'role:peminjam'])->group(function () {
        Route::get('/alat', function () {
            $alats = Alat::with('kategori')->where('kondisi', 'baik')->get();
            $kategoris = Kategori::all();
            $totalAlat = Alat::count();
            $totalTersedia = $alats->sum('stok_tersedia');
            $totalKategori = $kategoris->count();
            $alatRusak = Alat::where('kondisi', 'rusak')->count();
            
            return view('pages.alat.index', compact('alats', 'kategoris', 'totalAlat', 'totalTersedia', 'totalKategori', 'alatRusak'));
        })->name('alat.index');
    });

    // ========== ADMIN ONLY ROUTES ==========
    Route::middleware('role:admin')->group(function () {

        // ===== USERS =====
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // ===== KATEGORI =====
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
            LogAktivitas::createLog(auth()->id(), "Menghapus kategori: {$kategori->nama_kategori}", 'Kategori');
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        })->name('kategori.destroy');

        // ===== PEMINJAMAN (ADMIN MANAGEMENT) =====
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
                'alat_id' => $request->alat_id,
                'jumlah' => $request->jumlah,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'tujuan_peminjaman' => $request->tujuan_peminjaman,
                'status' => 'menunggu',
            ]);

            LogAktivitas::createLog(auth()->id(), "Admin menambah peminjaman alat: {$alat->nama_alat}", 'Peminjaman');
            return back()->with('success', 'Peminjaman berhasil ditambahkan!');
        })->name('peminjaman.store');

        // ===== PERSETUJUAN =====
        Route::get('/persetujuan', function () {
            $peminjamans = Peminjaman::where('status', 'menunggu')->with('user', 'alat')->latest('tanggal_peminjaman')->get();
            return view('pages.persetujuan.index', compact('peminjamans'));
        })->name('persetujuan.index');

        Route::post('/persetujuan/{peminjaman}/approve', function (Peminjaman $peminjaman) {
            if ($peminjaman->status !== 'menunggu') {
                return back()->with('error', 'Status peminjaman tidak valid!');
            }

            $peminjaman->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
            ]);

            $peminjaman->alat->decrement('stok_tersedia', $peminjaman->jumlah);
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

        // ===== PENGEMBALIAN =====
        Route::get('/pengembalian', function () {
            $peminjamanans = Peminjaman::where('status', 'disetujui')
                ->with('user', 'alat')
                ->latest('tanggal_peminjaman')
                ->get();
            return view('pages.pengembalian.index', compact('peminjamanans'));
        })->name('pengembalian.index');

        Route::post('/pengembalian', function (Request $request) {
            $request->validate([
                'peminjaman_id' => 'required|exists:peminjaman,peminjaman_id',
                'tanggal_kembali_aktual' => 'required|date',
                'kondisi_alat' => 'required|in:baik,rusak,hilang',
                'keterangan' => 'nullable|string',
            ]);

            $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
            
            if ($peminjaman->status !== 'disetujui') {
                return back()->with('error', 'Peminjaman tidak dalam status disetujui!');
            }

            Pengembalian::create([
                'peminjaman_id' => $request->peminjaman_id,
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'kondisi_alat' => $request->kondisi_alat,
                'keterangan' => $request->keterangan,
            ]);

            $peminjaman->update(['status' => 'dikembalikan']);
            $peminjaman->alat->increment('stok_tersedia', $peminjaman->jumlah);

            LogAktivitas::createLog(auth()->id(), "Memproses pengembalian alat: {$peminjaman->alat->nama_alat}", 'Pengembalian');
            return back()->with('success', 'Pengembalian berhasil dicatat!');
        })->name('pengembalian.store');

        // ===== LAPORAN =====
        Route::get('/laporan', function () {
            $peminjamans = Peminjaman::with('user', 'alat')->latest('tanggal_peminjaman')->get();
            return view('pages.laporan.index', compact('peminjamans'));
        })->name('laporan.index');

        // ===== LOG AKTIVITAS =====
        Route::get('/log', function () {
            $logs = LogAktivitas::with('user')->latest('timestamp')->paginate(20);
            return view('pages.log.index', compact('logs'));
        })->name('log.index');
    });
});