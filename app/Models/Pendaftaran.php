<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'lowongan_id',
        'nama',
        'nim',
        'nilai_matkul',
        'ipk',
        'motivasi',
        'portfolio',
        'cv_path',
        'transkrip_path',
        'status',
        'catatan_dosen',
        'status_seleksi',
        'is_published',
    ];

    protected $casts = [
        'ipk' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Pengumuman::class, 'lowongan_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
