<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'email',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin()
    {
        return $this->level === 'admin';
    }

    public function isPetugas()
    {
        return $this->level === 'petugas';
    }

    public function isPeminjam()
    {
        return $this->level === 'peminjam';
    }
}