<?php

namespace Tests\Feature;

use Tests\TestCase;

class PendaftaranStorageAndListTest extends TestCase
{
    public function test_pendaftaran_store_saves_files_to_public_storage(): void
    {
        $response = $this->post('/mahasiswa/pendaftaran', [
            'lowongan_id' => 1,
            'nama' => 'Aldo Mahasiswa',
            'nim' => '202310101',
            'nilai_matkul' => 'A',
            'ipk' => '3.70',
            'motivasi' => 'Saya sangat tertarik dan memiliki kemampuan yang dibutuhkan untuk membantu praktikum.',
            'portfolio' => 'https://example.com/portfolio',
            'cv' => new \Illuminate\Http\UploadedFile(
                __DIR__.'/fixtures/sample.pdf',
                'sample.pdf',
                'application/pdf',
                null,
                true
            ),
            'transkrip' => new \Illuminate\Http\UploadedFile(
                __DIR__.'/fixtures/sample.pdf',
                'sample.pdf',
                'application/pdf',
                null,
                true
            ),
            'persetujuan' => '1',
        ]);

        $response->assertRedirect('/mahasiswa/status');
    }

    public function test_dosen_can_list_pendaftar(): void
    {
        $response = $this->getJson('/dosen/pendaftar');

        $response->assertOk()
            ->assertJsonFragment(['catatan_dosen' => 'Mahasiswa memiliki performa akademik baik, namun masih perlu penguatan komunikasi saat praktikum.']);
    }

    public function test_mahasiswa_list_hides_private_dosen_notes(): void
    {
        $response = $this->getJson('/mahasiswa/pendaftar');

        $response->assertOk();
        $response->assertJsonMissingPath('0.catatan_dosen');
        $response->assertJsonMissingPath('1.catatan_dosen');
    }
}
