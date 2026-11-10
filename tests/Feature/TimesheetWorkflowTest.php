<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\Timesheet;
use Tests\TestCase;

class TimesheetWorkflowTest extends TestCase
{
    public function test_mahasiswa_can_submit_timesheet(): void
    {
        $lowongan = $this->createActiveLowongan();

        $response = $this->post('/mahasiswa/timesheet', [
            'matakuliah' => $lowongan->kode.' · '.$lowongan->mata_kuliah,
            'tanggal' => now()->subDay()->toDateString(),
            'jam' => '3.5',
            'aktivitas' => 'Mendampingi praktikum',
            'deskripsi' => 'Membantu mahasiswa menyelesaikan tugas di laboratorium.',
        ]);

        $response->assertRedirect('/mahasiswa/timesheet');

        $this->assertDatabaseHas('timesheet', [
            'asisten_id' => 1,
            'mata_kuliah' => $lowongan->kode.' · '.$lowongan->mata_kuliah,
            'aktivitas' => 'Mendampingi praktikum',
            'jumlah_jam' => 3.5,
            'status_approval' => 'pending',
        ]);
    }

    public function test_dosen_can_view_timesheet_detail(): void
    {
        $timesheet = Timesheet::query()->create([
            'asisten_id' => 7,
            'tanggal' => now()->subDay()->toDateString(),
            'jumlah_jam' => 4.5,
            'mata_kuliah' => '2403 · Algoritma Struktur Data',
            'aktivitas' => 'Mendampingi praktikum',
            'uraian_pekerjaan' => 'Membantu persiapan modul praktikum.',
            'status_approval' => 'pending',
            'catatan_revisi' => null,
        ]);

        $response = $this->get('/dosen/timesheet/'.$timesheet->id);

        $response->assertOk();
        $response->assertSee('Detail Timesheet')
            ->assertSee('Membantu persiapan modul praktikum.');
    }

    public function test_student_timesheet_page_renders_active_courses_and_empty_history(): void
    {
        $lowongan = $this->createActiveLowongan();

        $response = $this->get('/mahasiswa/timesheet');

        $response->assertOk()
            ->assertSee('Riwayat TimeSheet')
            ->assertSee($lowongan->kode.' · '.$lowongan->mata_kuliah)
            ->assertSee('Belum ada timesheet.');
    }

    public function test_dosen_timesheet_page_renders_persisted_entries(): void
    {
        $timesheet = $this->createPendingTimesheet();

        $response = $this->get('/dosen/timesheet');

        $response->assertOk()
            ->assertSee('Review Timesheet')
            ->assertSee($timesheet->aktivitas)
            ->assertSee('Menunggu');
    }

    public function test_dosen_can_approve_pending_timesheet(): void
    {
        $timesheet = $this->createPendingTimesheet();

        $response = $this->patch('/dosen/timesheet/'.$timesheet->id.'/status', [
            'status' => 'approved',
        ]);

        $response->assertRedirect('/dosen/timesheet/'.$timesheet->id);
        $this->assertDatabaseHas('timesheet', [
            'id' => $timesheet->id,
            'status_approval' => 'approved',
            'catatan_revisi' => null,
        ]);
    }

    public function test_dosen_must_add_a_note_when_rejecting_timesheet(): void
    {
        $timesheet = $this->createPendingTimesheet();

        $response = $this->patch('/dosen/timesheet/'.$timesheet->id.'/status', [
            'status' => 'rejected',
        ]);

        $response->assertSessionHasErrors('catatan_revisi');
        $this->assertDatabaseHas('timesheet', [
            'id' => $timesheet->id,
            'status_approval' => 'pending',
        ]);
    }

    public function test_dosen_can_reject_timesheet_with_a_note(): void
    {
        $timesheet = $this->createPendingTimesheet();

        $response = $this->patch('/dosen/timesheet/'.$timesheet->id.'/status', [
            'status' => 'rejected',
            'catatan_revisi' => 'Mohon lengkapi rincian aktivitas.',
        ]);

        $response->assertRedirect('/dosen/timesheet/'.$timesheet->id);
        $this->assertDatabaseHas('timesheet', [
            'id' => $timesheet->id,
            'status_approval' => 'rejected',
            'catatan_revisi' => 'Mohon lengkapi rincian aktivitas.',
        ]);
    }

    public function test_student_cannot_submit_a_timesheet_for_an_unavailable_course(): void
    {
        $response = $this->post('/mahasiswa/timesheet', [
            'matakuliah' => 'Course that is not open',
            'tanggal' => now()->subDay()->toDateString(),
            'jam' => '2',
            'aktivitas' => 'Mendampingi praktikum',
            'deskripsi' => 'Membantu mahasiswa menyelesaikan tugas.',
        ]);

        $response->assertSessionHasErrors('matakuliah');
        $this->assertDatabaseCount('timesheet', 0);
    }

    public function test_dosen_cannot_review_a_timesheet_twice(): void
    {
        $timesheet = $this->createPendingTimesheet();
        $this->patch('/dosen/timesheet/'.$timesheet->id.'/status', ['status' => 'approved']);

        $response = $this->patch('/dosen/timesheet/'.$timesheet->id.'/status', [
            'status' => 'rejected',
            'catatan_revisi' => 'Sudah ditinjau.',
        ]);

        $response->assertConflict();
        $this->assertDatabaseHas('timesheet', [
            'id' => $timesheet->id,
            'status_approval' => 'approved',
            'catatan_revisi' => null,
        ]);
    }

    public function test_dosen_can_bulk_approve_pending_timesheets(): void
    {
        $first = $this->createPendingTimesheet();
        $second = $this->createPendingTimesheet();

        $response = $this->from('/dosen/timesheet')->patch('/dosen/timesheet/approve-pending');

        $response->assertRedirect('/dosen/timesheet');
        $this->assertDatabaseHas('timesheet', [
            'id' => $first->id,
            'status_approval' => 'approved',
        ]);
        $this->assertDatabaseHas('timesheet', [
            'id' => $second->id,
            'status_approval' => 'approved',
        ]);
    }

    private function createActiveLowongan(): Pengumuman
    {
        return Pengumuman::query()->create([
            'dosen_id' => 1,
            'mata_kuliah' => 'Algoritma Struktur Data',
            'kode' => '2403',
            'kuota' => 2,
            'syarat' => 'Nilai minimum B.',
            'berkas_diminta' => json_encode(['CV', 'Transkrip nilai']),
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ]);
    }

    private function createPendingTimesheet(): Timesheet
    {
        return Timesheet::query()->create([
            'asisten_id' => 1,
            'tanggal' => now()->subDay()->toDateString(),
            'jumlah_jam' => 2.5,
            'mata_kuliah' => '2403 · Algoritma Struktur Data',
            'aktivitas' => 'Mendampingi praktikum',
            'uraian_pekerjaan' => 'Membantu mahasiswa di laboratorium.',
            'status_approval' => 'pending',
        ]);
    }
}
