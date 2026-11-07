<?php

namespace Tests\Feature;

use App\Models\Timesheet;
use Tests\TestCase;

class TimesheetWorkflowTest extends TestCase
{
    public function test_mahasiswa_can_submit_timesheet(): void
    {
        $response = $this->post('/mahasiswa/timesheet', [
            'matakuliah' => '2403 · Algoritma Struktur Data',
            'tanggal' => '2026-10-20',
            'jam' => '3.5',
            'aktivitas' => 'Mendampingi praktikum',
            'deskripsi' => 'Membantu mahasiswa menyelesaikan tugas di laboratorium.',
        ]);

        $response->assertRedirect('/mahasiswa/timesheet');

        $this->assertDatabaseHas('timesheet', [
            'asisten_id' => 1,
            'status_approval' => 'pending',
        ]);
    }

    public function test_dosen_can_view_timesheet_detail(): void
    {
        $timesheet = Timesheet::query()->create([
            'asisten_id' => 7,
            'tanggal' => '2026-10-25',
            'jumlah_jam' => 4.5,
            'uraian_pekerjaan' => 'Membantu persiapan modul praktikum.',
            'status_approval' => 'pending',
            'catatan_revisi' => null,
        ]);

        $response = $this->get('/dosen/timesheet/'.$timesheet->id);

        $response->assertOk();
        $response->assertSee('Detail Timesheet Asisten Praktikum Dosen');
    }
}
