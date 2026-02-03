<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

Route::get('/login', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string|min:6',
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors(['login' => 'Username atau password salah!'])->onlyInput('username');
    }

    auth()->login($user);
    LogAktivitas::createLog($user->user_id, 'Login ke sistem', 'Auth');

    return redirect()->route('dashboard')->with('success', 'Login berhasil!');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    if (auth()->check()) {
        LogAktivitas::createLog(auth()->id(), 'Logout dari sistem', 'Auth');
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    return redirect()->route('login')->with('success', 'Logout berhasil!');
})->name('logout');

// ============ PROTECTED ROUTES ============

Route::middleware('auth')->group(function () {
    
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

    // DAFTAR ALAT (SEMUA)
    Route::get('/daftar-alat', function () {
        $alats = Alat::with('kategori')->where('stok_tersedia', '>', 0)->paginate(12);
        return view('pages.daftar-alat.index', compact('alats'));
    })->name('daftar-alat.index');

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

    // ========== ADMIN ROUTES ==========
    Route::middleware('admin')->group(function () {

        // USERS
        Route::get('/users', function () {
            $users = User::paginate(10);
            return view('pages.users.index', compact('users'));
        })->name('users.index');

        Route::post('/users', function (Request $request) {
            $request->validate([
                'username' => 'required|string|unique:users|min:3',
                'password' => 'required|string|min:6',
                'level' => 'required|in:admin,petugas,peminjam',
            ]);

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'level' => $request->level,
            ]);

            LogAktivitas::createLog(auth()->id(), "Menambah user: {$user->username}", 'User');
            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
        })->name('users.store');

        Route::put('/users/{user}', function (Request $request, User $user) {
            $request->validate([
                'username' => 'required|string|unique:users,username,' . $user->user_id . ',user_id|min:3',
                'level' => 'required|in:admin,petugas,peminjam',
                'password' => 'nullable|string|min:6',
            ]);

            $user->update([
                'username' => $request->username,
                'level' => $request->level,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            LogAktivitas::createLog(auth()->id(), "Mengubah user: {$user->username}", 'User');
            return redirect()->route('users.index')->with('success', 'User berhasil diubah!');
        })->name('users.update');

        Route::delete('/users/{user}', function (User $user) {
            if ($user->user_id === auth()->id()) {
                return back()->with('error', 'Tidak bisa hapus akun sendiri!');
            }
            LogAktivitas::createLog(auth()->id(), "Menghapus user: {$user->username}", 'User');
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        })->name('users.destroy');

        // KATEGORI
        Route::get('/kategori', function () {
            $kategoris = Kategori::paginate(10);
            return view('pages.kategori.index', compact('kategoris'));
        })->name('kategori.index');

        Route::post('/kategori', function (Request $request) {
            $request->validate([
                'nama_kategori' => 'required|string|unique:kategori',
                'deskripsi' => 'nullable|string',
            ]);

            Kategori::create($request->only('nama_kategori', 'deskripsi'));
            LogAktivitas::createLog(auth()->id(), "Menambah kategori: {$request->nama_kategori}", 'Kategori');
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
        })->name('kategori.store');

        Route::put('/kategori/{kategori}', function (Request $request, Kategori $kategori) {
            $request->validate([
                'nama_kategori' => 'required|string|unique:kategori,nama_kategori,' . $kategori->kategori_id . ',kategori_id',
                'deskripsi' => 'nullable|string',
            ]);

            $kategori->update($request->only('nama_kategori', 'deskripsi'));
            LogAktivitas::createLog(auth()->id(), "Mengubah kategori: {$kategori->nama_kategori}", 'Kategori');
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah!');
        })->name('kategori.update');

        Route::delete('/kategori/{kategori}', function (Kategori $kategori) {
            LogAktivitas::createLog(auth()->id(), "Menghapus kategori: {$kategori->nama_kategori}", 'Kategori');
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        })->name('kategori.destroy');

        // ALAT
        Route::get('/alat', function () {
            $alats = Alat::with('kategori')->paginate(10);
            return view('pages.alat.index', compact('alats'));
        })->name('alat.index');

        Route::post('/alat', function (Request $request) {
            $request->validate([
                'nama_alat' => 'required|string',
                'kategori_id' => 'required|exists:kategori,kategori_id',
                'kode_alat' => 'required|string|unique:alat',
                'stok_total' => 'required|integer|min:1',
                'lokasi' => 'nullable|string',
                'kondisi' => 'required|in:baik,rusak,hilang',
                'deskripsi' => 'nullable|string',
            ]);

            $alat = Alat::create([
                'nama_alat' => $request->nama_alat,
                'kategori_id' => $request->kategori_id,
                'kode_alat' => $request->kode_alat,
                'stok_total' => $request->stok_total,
                'stok_tersedia' => $request->stok_total,
                'lokasi' => $request->lokasi,
                'kondisi' => $request->kondisi,
                'deskripsi' => $request->deskripsi,
            ]);

            LogAktivitas::createLog(auth()->id(), "Menambah alat: {$alat->nama_alat}", 'Alat');
            return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan!');
        })->name('alat.store');

        Route::put('/alat/{alat}', function (Request $request, Alat $alat) {
            $request->validate([
                'nama_alat' => 'required|string',
                'kategori_id' => 'required|exists:kategori,kategori_id',
                'kode_alat' => 'required|string|unique:alat,kode_alat,' . $alat->alat_id . ',alat_id',
                'stok_total' => 'required|integer|min:1',
                'lokasi' => 'nullable|string',
                'kondisi' => 'required|in:baik,rusak,hilang',
                'deskripsi' => 'nullable|string',
            ]);

            $alat->update($request->only('nama_alat', 'kategori_id', 'kode_alat', 'stok_total', 'lokasi', 'kondisi', 'deskripsi'));
            LogAktivitas::createLog(auth()->id(), "Mengubah alat: {$alat->nama_alat}", 'Alat');
            return redirect()->route('alat.index')->with('success', 'Alat berhasil diubah!');
        })->name('alat.update');

        Route::delete('/alat/{alat}', function (Alat $alat) {
            LogAktivitas::createLog(auth()->id(), "Menghapus alat: {$alat->nama_alat}", 'Alat');
            $alat->delete();
            return redirect()->route('alat.index')->with('success', 'Alat berhasil dihapus!');
        })->name('alat.destroy');

        // PEMINJAMAN
        Route::get('/peminjaman', function () {
            $peminjamans = Peminjaman::with('user', 'alat')->paginate(10);
            return view('pages.peminjaman.index', compact('peminjamans'));
        })->name('peminjaman.index');

        Route::post('/peminjaman/{peminjaman}/approve', function (Peminjaman $peminjaman) {
            if ($peminjaman->status !== 'menunggu') {
                return back()->with('error', 'Status peminjaman tidak valid!');
            }

            $peminjaman->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_disetujui' => now(),
            ]);

            $peminjaman->alat->decrement('stok_tersedia', $peminjaman->jumlah);
            LogAktivitas::createLog(auth()->id(), "Menyetujui peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');

            return back()->with('success', 'Peminjaman disetujui!');
        })->name('peminjaman.approve');

        Route::post('/peminjaman/{peminjaman}/reject', function (Peminjaman $peminjaman) {
            if ($peminjaman->status !== 'menunggu') {
                return back()->with('error', 'Status peminjaman tidak valid!');
            }

            $peminjaman->update(['status' => 'ditolak']);
            LogAktivitas::createLog(auth()->id(), "Menolak peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');

            return back()->with('success', 'Peminjaman ditolak!');
        })->name('peminjaman.reject');

        Route::delete('/peminjaman/{peminjaman}', function (Peminjaman $peminjaman) {
            LogAktivitas::createLog(auth()->id(), "Menghapus peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');
            $peminjaman->delete();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman dihapus!');
        })->name('peminjaman.destroy');

        // LOG AKTIVITAS
        Route::get('/log', function () {
            $logs = LogAktivitas::with('user')->latest('timestamp')->paginate(20);
            return view('pages.log.index', compact('logs'));
        })->name('log.index');

    });

    // ========== PETUGAS ROUTES ==========
    Route::middleware('petugas')->group(function () {

        // PERSETUJUAN
        Route::get('/persetujuan', function () {
            $peminjamans = Peminjaman::where('status', 'menunggu')->with('user', 'alat')->paginate(10);
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
            LogAktivitas::createLog(auth()->id(), "Menyetujui peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');

            return back()->with('success', 'Peminjaman disetujui!');
        })->name('persetujuan.approve');

        Route::post('/persetujuan/{peminjaman}/reject', function (Peminjaman $peminjaman) {
            if ($peminjaman->status !== 'menunggu') {
                return back()->with('error', 'Status peminjaman tidak valid!');
            }

            $peminjaman->update(['status' => 'ditolak']);
            LogAktivitas::createLog(auth()->id(), "Menolak peminjaman ID: {$peminjaman->peminjaman_id}", 'Peminjaman');

            return back()->with('success', 'Peminjaman ditolak!');
        })->name('persetujuan.reject');

        // PENGEMBALIAN
        Route::get('/pengembalian', function () {
            $pengembalians = Pengembalian::with('peminjaman.user', 'peminjaman.alat')->paginate(10);
            return view('pages.pengembalian.index', compact('pengembalians'));
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
                return back()->with('error', 'Hanya peminjaman disetujui yang bisa dikembalikan!');
            }

            $tglKembali = strtotime($request->tanggal_kembali_aktual);
            $tglRencana = strtotime($peminjaman->tanggal_kembali_rencana);
            $telat = max(0, ceil(($tglKembali - $tglRencana) / 86400));
            $denda = $telat > 0 ? $telat * 50000 : 0;

            Pengembalian::create([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'kondisi_alat' => $request->kondisi_alat,
                'keterlambatan_hari' => $telat,
                'tarif_denda_per_hari' => $telat > 0 ? 50000 : 0,
                'total_denda' => $denda,
                'status_denda' => $denda > 0 ? 'belum_lunas' : 'lunas',
                'keterangan' => $request->keterangan,
            ]);

            $peminjaman->update(['status' => 'dikembalikan']);
            $peminjaman->alat->increment('stok_tersedia', $peminjaman->jumlah);

            LogAktivitas::createLog(auth()->id(), "Mencatat pengembalian peminjaman ID: {$peminjaman->peminjaman_id}", 'Pengembalian');

            return back()->with('success', 'Pengembalian berhasil dicatat!');
        })->name('pengembalian.store');

        // LAPORAN
        Route::get('/laporan', function (Request $request) {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            
            $stats = [
                'total_peminjaman' => Peminjaman::whereDate('tanggal_peminjaman', $tanggal)->count(),
                'total_pengembalian' => Pengembalian::whereDate('tanggal_kembali_aktual', $tanggal)->count(),
                'total_denda' => Pengembalian::whereDate('created_at', $tanggal)->sum('total_denda'),
            ];

            $peminjamans = Peminjaman::whereDate('tanggal_peminjaman', $tanggal)->with('user', 'alat')->get();
            $pengembalians = Pengembalian::whereDate('tanggal_kembali_aktual', $tanggal)->with('peminjaman.user', 'peminjaman.alat')->get();

            return view('pages.laporan.index', compact('stats', 'peminjamans', 'pengembalians', 'tanggal'));
        })->name('laporan.index');

    });

});