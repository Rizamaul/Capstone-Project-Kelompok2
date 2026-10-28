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
}