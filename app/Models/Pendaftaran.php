<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    // Mengarahkan model ke tabel pendaftaran yang sudah Anda buat
    protected $table = 'pendaftaran';

    // Mengizinkan insert/update untuk semua kolom kecuali 'id'
    protected $guarded = ['id'];

    // Cast tipe data agar sesuai dengan migration
    protected $casts = [
        'ipk' => 'float',
        'is_published' => 'boolean',
    ];

    // Relasi balik ke tabel pengumuman (lowongan)
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'lowongan_id', 'id');
    }
}