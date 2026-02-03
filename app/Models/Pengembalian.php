<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'pengembalian_id';
    public $timestamps = true;

    protected $fillable = [
        'peminjaman_id', 'tanggal_kembali_aktual', 'kondisi_alat',
        'keterlambatan_hari', 'tarif_denda_per_hari', 'total_denda',
        'status_denda', 'keterangan'
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
        'tarif_denda_per_hari' => 'decimal:2',
        'total_denda' => 'decimal:2',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }
}