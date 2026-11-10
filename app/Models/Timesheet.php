<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'asisten_id',
    'tanggal',
    'jumlah_jam',
    'mata_kuliah',
    'aktivitas',
    'uraian_pekerjaan',
    'status_approval',
    'catatan_revisi',
])]
class Timesheet extends Model
{
    protected $table = 'timesheet';

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah_jam' => 'decimal:2',
        ];
    }

    public function asisten(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asisten_id');
    }
}
