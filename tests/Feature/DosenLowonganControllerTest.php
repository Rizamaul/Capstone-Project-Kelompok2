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
