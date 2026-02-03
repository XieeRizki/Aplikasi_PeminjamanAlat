<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'alat_id';
    public $timestamps = true;

    protected $fillable = [
        'kategori_id', 'nama_alat', 'deskripsi', 'kode_alat',
        'stok_total', 'stok_tersedia', 'kondisi', 'lokasi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'alat_id', 'alat_id');
    }
}