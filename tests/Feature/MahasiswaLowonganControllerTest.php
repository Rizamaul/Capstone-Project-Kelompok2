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

    public function test_lowongan_can_be_filtered_by_keyword_query(): void
    {
        $match = $this->createLowongan(['mata_kuliah' => 'Basis Data Lanjut', 'kode' => 'BD202']);
        $other = $this->createLowongan(['mata_kuliah' => 'Kecerdasan Buatan', 'kode' => 'AI301']);

        $response = $this->get('/mahasiswa/lowongan?q=Basis');

        $response->assertOk()
            ->assertSee($match->mata_kuliah)
            ->assertDontSee($other->mata_kuliah);
    }

    public function test_lowongan_can_be_filtered_by_status_query(): void
    {
        $open = $this->createLowongan(['mata_kuliah' => 'Sistem Operasi', 'status_aktif' => true]);
        $closed = $this->createLowongan(['mata_kuliah' => 'Jaringan Komputer', 'status_aktif' => false]);

        $response = $this->get('/mahasiswa/lowongan?status=Dibuka');

        $response->assertOk()
            ->assertSee($open->mata_kuliah)
            ->assertDontSee($closed->mata_kuliah);
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
