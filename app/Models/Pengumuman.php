<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'dosen_id',
        'mata_kuliah',
        'kuota',
        'syarat',
        'berkas_diminta',
        'tenggat',
        'status_aktif',
    ];

    protected $casts = [
        'kuota' => 'integer',
        'tenggat' => 'datetime',
        'status_aktif' => 'boolean',
    ];

    /**
     * Relasi ke tabel Pendaftaran
     * 1 Pengumuman/Lowongan memiliki BANYAK Pendaftaran (HasMany)
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'lowongan_id', 'id');
    }

    /**
     * Opsional: Relasi ke tabel Users (Dosen pembuat pengumuman)
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }
}