<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'aktivitas', 'modul', 'timestamp'];

    protected $casts = ['timestamp' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public static function createLog($userId, $aktivitas, $modul)
    {
        return self::create([
            'user_id' => $userId,
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'timestamp' => now(),
        ]);
    }
}