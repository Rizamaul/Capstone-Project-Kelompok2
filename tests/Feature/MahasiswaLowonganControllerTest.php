<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use Tests\TestCase;

class MahasiswaLowonganControllerTest extends TestCase
{
    public function test_lowongan_page_renders_database_announcements(): void
    {
        $lowongan = $this->createLowongan();

        $response = $this->get('/mahasiswa/lowongan');

        $response->assertOk()
            ->assertSee($lowongan->mata_kuliah)
            ->assertSee($lowongan->kode);
    }

    public function test_lowongan_detail_shows_saved_requirements_and_requested_documents(): void
    {
        $lowongan = $this->createLowongan();

        $response = $this->get('/mahasiswa/lowongan/'.$lowongan->id);

        $response->assertOk()
            ->assertSee($lowongan->syarat)
            ->assertSee('CV')
            ->assertSee('Transkrip nilai')
            ->assertSee('3,50');
    }

    public function test_closed_lowongan_cannot_be_applied_to(): void
    {
        $lowongan = $this->createLowongan(['status_aktif' => false]);

        $response = $this->get('/mahasiswa/pendaftaran/'.$lowongan->id);

        $response->assertNotFound();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createLowongan(array $attributes = []): Pengumuman
    {
        return Pengumuman::query()->create(array_merge([
            'dosen_id' => 1,
            'mata_kuliah' => 'Algoritma Struktur Data',
            'kode' => '2403',
            'sks' => 3,
            'kelas' => 'A',
            'semester' => 'Ganjil 2026',
            'kuota' => 2,
            'jadwal' => 'Senin 08.00',
            'syarat' => 'Wajib menguasai dasar pemrograman.',
            'syarat_nilai' => 'B+',
            'syarat_ipk' => 3.5,
            'deskripsi' => 'Mendampingi praktikum algoritma.',
            'berkas_diminta' => json_encode(['CV', 'Transkrip nilai']),
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ], $attributes));
    }
}
