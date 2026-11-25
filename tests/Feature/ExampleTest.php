<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_homepage_offers_student_and_professor_dashboards(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Pilih ruang kerjamu');
        $response->assertSee('Mahasiswa');
        $response->assertSee('Dosen');
        $response->assertSee(route('mahasiswa.dashboard', absolute: false), false);
        $response->assertSee(route('dosen.dashboard', absolute: false), false);
    }

    public function test_student_dashboard_links_back_to_the_role_picker(): void
    {
        $response = $this->get(route('mahasiswa.dashboard', absolute: false));

        $response->assertOk();
        $response->assertSee('Keluar');
        $response->assertSee('href="'.route('home').'"', false);
    }

    public function test_professor_dashboard_links_back_to_the_role_picker(): void
    {
        $response = $this->get(route('dosen.dashboard', absolute: false));

        $response->assertOk();
        $response->assertSee('Keluar');
        $response->assertSee('href="'.route('home').'"', false);
    }

    public function test_role_notifications_popover_renders_for_dosen_and_mahasiswa(): void
    {
        $lowongan = \App\Models\Pengumuman::query()->create([
            'dosen_id' => 1,
            'mata_kuliah' => 'Basis Data',
            'kode' => 'IF202',
            'sks' => 3,
            'kuota' => 2,
            'jadwal' => 'Selasa 09.00',
            'syarat' => 'Nilai B',
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ]);

        \App\Models\Pendaftaran::query()->create([
            'lowongan_id' => $lowongan->id,
            'nama' => 'Budi Santoso',
            'nim' => '105223001',
            'status' => 'menunggu',
            'berkas_diverifikasi' => false,
            'status_berkas' => 'menunggu',
        ]);

        $dosenResponse = $this->get(route('dosen.dashboard', absolute: false));
        $dosenResponse->assertOk()
            ->assertSee('Pendaftar Baru Menunggu Verifikasi')
            ->assertSee('Budi Santoso melamar pada Basis Data.');

        $mahasiswaResponse = $this->get(route('mahasiswa.dashboard', absolute: false));
        $mahasiswaResponse->assertOk()
            ->assertSee('Pendaftaran Terkirim')
            ->assertSee('Lamaran Basis Data sedang menunggu peninjauan dosen.');
    }
}
