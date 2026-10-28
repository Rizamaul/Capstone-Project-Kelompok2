<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'asisten_id',
    'tanggal',
    'jumlah_jam',
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
}
