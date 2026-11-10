<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use Tests\TestCase;

class DosenDashboardControllerTest extends TestCase
{
    public function test_dashboard_renders_persisted_recruitment_announcements(): void
    {
        Pengumuman::query()->create([
            'dosen_id' => 1,
            'mata_kuliah' => 'Jaringan Komputer',
            'kode' => '2404',
            'sks' => 3,
            'kuota' => 2,
            'jadwal' => 'Rabu 10.00',
            'syarat' => 'Nilai minimum B.',
            'deskripsi' => 'Mendampingi praktikum jaringan.',
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ]);

        $response = $this->get('/dosen/dashboard');

        $response->assertOk()
            ->assertSee('Pengumuman terbaru')
            ->assertSee('Jaringan Komputer')
            ->assertSee('2404');
    }
}
