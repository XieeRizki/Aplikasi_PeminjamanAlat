<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'peminjaman_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'alat_id', 'jumlah', 'tanggal_peminjaman',
        'tanggal_kembali_rencana', 'tujuan_peminjaman', 'status',
        'disetujui_oleh', 'tanggal_disetujui'
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_disetujui' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'user_id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id', 'peminjaman_id');
    }
}