<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Tests\TestCase;

class DosenLowonganControllerTest extends TestCase
{
    public function test_dosen_lowongan_index_returns_announcements(): void
    {
        $this->createLowongan();

        $response = $this->getJson('/dosen/lowongan');

        $response->assertOk()
            ->assertJsonStructure([
                ['id', 'nama', 'kode', 'status'],
            ]);
    }

    public function test_dosen_lowongan_can_toggle_status(): void
    {
        $lowongan = $this->createLowongan();

        $response = $this->patchJson('/dosen/lowongan/'.$lowongan->id.'/status', [
            'status' => 'tutup',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'tutup');

        $this->patchJson('/dosen/lowongan/'.$lowongan->id.'/status', [
            'status' => 'aktif',
        ])->assertOk()
            ->assertJsonPath('data.status', 'aktif');
    }

    public function test_dosen_can_create_a_lowongan(): void
    {
        $response = $this->postJson('/dosen/lowongan', [
            'nama' => 'Jaringan Komputer',
            'kode' => '2404',
            'sks' => 3,
            'kuota' => 2,
            'jadwal' => 'Rabu 10.00',
            'tenggat' => now()->addWeek()->toDateString(),
            'syarat' => 'Nilai minimum B.',
            'deskripsi' => 'Mendampingi praktikum jaringan.',
            'status' => 'aktif',
            'berkas_diminta' => ['CV'],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.nama', 'Jaringan Komputer');
        $this->assertDatabaseHas('pengumuman', [
            'mata_kuliah' => 'Jaringan Komputer',
            'kode' => '2404',
            'status_aktif' => true,
        ]);
    }

    public function test_dosen_created_lowongan_is_interconnected_with_mahasiswa_portal(): void
    {
        $response = $this->post('/dosen/lowongan', [
            'nama' => 'Pemrograman Web Lanjut',
            'kode' => 'PW2026',
            'kelas' => 'Kelas 2402',
            'semester' => 'Semester Ganjil 2026',
            'kuota' => 3,
            'tenggat' => now()->addWeeks(2)->toDateTimeString(),
            'syarat_nilai' => 'A-',
            'syarat_ipk' => 3.25,
            'syarat_tambahan' => [
                'Menguasai framework Laravel dan Tailwind CSS.',
            ],
            'manfaat' => [
                'Konversi 2 SKS dan honor asistensi.',
            ],
            'berkas_diminta' => ['CV', 'Transkrip nilai'],
            'berkas_diminta_present' => '1',
        ]);

        $response->assertRedirect('/dosen/lowongan');

        $this->assertDatabaseHas('pengumuman', [
            'mata_kuliah' => 'Pemrograman Web Lanjut',
            'kode' => 'PW2026',
            'status_aktif' => true,
        ]);

        $mahasiswaLowonganResponse = $this->get('/mahasiswa/lowongan');
        $mahasiswaLowonganResponse->assertOk()
            ->assertSee('Pemrograman Web Lanjut')
            ->assertSee('PW2026');

        $mahasiswaDashboardResponse = $this->get('/mahasiswa/dashboard');
        $mahasiswaDashboardResponse->assertOk()
            ->assertSee('Pemrograman Web Lanjut');
    }

    public function test_dosen_can_update_a_lowongan_and_clear_requested_documents(): void
    {
        $lowongan = $this->createLowongan();

        $response = $this->put('/dosen/lowongan/'.$lowongan->id, [
            'nama' => 'Algoritma dan Struktur Data',
            'status' => 'aktif',
            'berkas_diminta_present' => '1',
        ]);

        $response->assertRedirect('/dosen/lowongan');
        $this->assertDatabaseHas('pengumuman', [
            'id' => $lowongan->id,
            'mata_kuliah' => 'Algoritma dan Struktur Data',
            'berkas_diminta' => '[]',
        ]);
    }

    public function test_dosen_cannot_delete_a_lowongan_with_applicants(): void
    {
        $lowongan = $this->createLowongan();
        Pendaftaran::query()->create([
            'lowongan_id' => $lowongan->id,
            'nama' => 'Mahasiswa Penguji',
            'nim' => '202600001',
            'status' => 'menunggu',
        ]);

        $response = $this->deleteJson('/dosen/lowongan/'.$lowongan->id);

        $response->assertConflict();
        $this->assertModelExists($lowongan);
    }

    public function test_dosen_can_create_lowongan_with_custom_ipk_and_grade_c(): void
    {
        $response = $this->post('/dosen/lowongan', [
            'nama' => 'Sistem Berkas',
            'kode' => 'SB101',
            'kelas' => '2402',
            'kuota' => 2,
            'tenggat' => now()->addWeeks(2)->toDateTimeString(),
            'syarat_nilai' => 'C',
            'syarat_ipk' => '3,15',
        ]);

        $response->assertRedirect('/dosen/lowongan');
        $this->assertDatabaseHas('pengumuman', [
            'mata_kuliah' => 'Sistem Berkas',
            'syarat_nilai' => 'C',
            'syarat_ipk' => 3.15,
        ]);
    }

    public function test_dosen_cannot_create_lowongan_with_ipk_over_4_or_non_positive(): void
    {
        $responseOver = $this->from('/dosen/lowongan/create')->post('/dosen/lowongan', [
            'nama' => 'Sistem Operasi',
            'kode' => 'SO101',
            'kuota' => 2,
            'tenggat' => now()->addWeeks(2)->toDateTimeString(),
            'syarat_ipk' => '4.25',
        ]);
        $responseOver->assertSessionHasErrors(['syarat_ipk']);

        $responseZero = $this->from('/dosen/lowongan/create')->post('/dosen/lowongan', [
            'nama' => 'Sistem Operasi',
            'kode' => 'SO101',
            'kuota' => 2,
            'tenggat' => now()->addWeeks(2)->toDateTimeString(),
            'syarat_ipk' => '0',
        ]);
        $responseZero->assertSessionHasErrors(['syarat_ipk']);

        $responseMinus = $this->from('/dosen/lowongan/create')->post('/dosen/lowongan', [
            'nama' => 'Sistem Operasi',
            'kode' => 'SO101',
            'kuota' => 2,
            'tenggat' => now()->addWeeks(2)->toDateTimeString(),
            'syarat_ipk' => '-1.5',
        ]);
        $responseMinus->assertSessionHasErrors(['syarat_ipk']);
    }

    private function createLowongan(): Pengumuman
    {
        return Pengumuman::query()->create([
            'dosen_id' => 1,
            'mata_kuliah' => 'Algoritma Struktur Data',
            'kode' => '2403',
            'sks' => 3,
            'kuota' => 2,
            'jadwal' => 'Senin 08.00',
            'syarat' => 'Nilai minimum B.',
            'berkas_diminta' => json_encode(['CV', 'Transkrip nilai']),
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ]);
    }
}
