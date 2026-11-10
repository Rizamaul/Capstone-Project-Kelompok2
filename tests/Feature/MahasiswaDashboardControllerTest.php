<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use Tests\TestCase;

class MahasiswaDashboardControllerTest extends TestCase
{
    public function test_dashboard_renders_open_database_announcements(): void
    {
        Pengumuman::query()->create([
            'dosen_id' => 1,
            'mata_kuliah' => 'Pemrograman Dasar',
            'kode' => 'IF101',
            'sks' => 3,
            'kuota' => 2,
            'jadwal' => 'Senin 08.00',
            'syarat' => 'Nilai minimum B.',
            'deskripsi' => 'Membantu sesi praktikum.',
            'berkas_diminta' => json_encode(['CV']),
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ]);

        $response = $this->get('/mahasiswa/dashboard');

        $response->assertOk()
            ->assertSee('Pengumuman Aktif')
            ->assertSee('Pemrograman Dasar')
            ->assertSee('IF101');
    }
}
