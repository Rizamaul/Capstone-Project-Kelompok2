<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'dosen_id',
        'mata_kuliah',
        'kode',
        'sks',
        'kelas',
        'semester',
        'kuota',
        'jadwal',
        'syarat_nilai',
        'syarat_ipk',
        'syarat',
        'deskripsi',
        'berkas_diminta',
        'tenggat',
        'status_aktif',
    ];

    protected $casts = [
        'kuota' => 'integer',
        'sks' => 'integer',
        'syarat_ipk' => 'decimal:2',
        'tenggat' => 'datetime',
        'status_aktif' => 'boolean',
    ];

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'lowongan_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
