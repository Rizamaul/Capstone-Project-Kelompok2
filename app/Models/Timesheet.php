<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'timesheet';

    // Menggunakan satu standar $fillable yang selaras dengan form dan controller
    protected $fillable = [
        'user_id', 
        'matakuliah', 
        'tanggal', 
        'jam', 
        'aktivitas', 
        'deskripsi', 
        'status', 
        'catatan_revisi_dosen'
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jam'     => 'decimal:2', // Diubah dari 'jumlah_jam' agar sesuai dengan nama kolom di $fillable
        ];
    }

    /**
     * Relasi ke model User (Mahasiswa)
     * Sangat berguna saat Dosen ingin melihat siapa yang submit timesheet ini
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}