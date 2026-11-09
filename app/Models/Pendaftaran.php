<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $guarded = ['id'];

    // Relasi ke User (Mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Pengumuman (Lowongan)
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'lowongan_id');
    }
}