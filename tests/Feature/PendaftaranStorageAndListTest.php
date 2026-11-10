<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PendaftaranStorageAndListTest extends TestCase
{
    public function test_pendaftaran_store_saves_files_to_public_storage(): void
    {
        Storage::fake('public');
        $lowongan = $this->createLowongan();

        $response = $this->post('/mahasiswa/pendaftaran', $this->validApplicationData($lowongan));

        $response->assertRedirect('/mahasiswa/status');
        $pendaftaran = Pendaftaran::query()->sole();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'lowongan_id' => $lowongan->id,
            'nim' => '202310101',
            'status' => 'menunggu',
        ]);
        Storage::disk('public')->assertExists($pendaftaran->cv_path);
        Storage::disk('public')->assertExists($pendaftaran->transkrip_path);
    }

    public function test_application_form_displays_lowongan_specific_academic_requirements(): void
    {
        $lowongan = $this->createLowongan([
            'syarat_nilai' => 'B+',
            'syarat_ipk' => 3.5,
        ]);

        $response = $this->get('/mahasiswa/pendaftaran/'.$lowongan->id);

        $response->assertOk()
            ->assertSee('minimal B+')
            ->assertSee('minimal 3,50')
            ->assertSee('name="ipk"', false)
            ->assertSee('required');
    }

    public function test_dosen_can_list_pendaftar(): void
    {
        $pendaftaran = $this->createPendaftaran();

        $response = $this->get('/dosen/pendaftar');

        $response->assertOk()
            ->assertSee($pendaftaran->nama)
            ->assertSee('Mahasiswa memiliki performa akademik baik');
    }

    public function test_dosen_can_publish_an_application_decision(): void
    {
        $pendaftaran = $this->createPendaftaran();

        $response = $this->from('/dosen/pendaftar')->patch('/dosen/pendaftar/'.$pendaftaran->id.'/status', [
            'status_seleksi' => 'disetujui',
            'catatan_dosen' => 'Selamat, pendaftaran disetujui.',
        ]);

        $response->assertRedirect('/dosen/pendaftar');
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
            'is_published' => true,
        ]);
    }

    public function test_dosen_cannot_publish_a_second_decision(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
            'is_published' => true,
        ]);

        $response = $this->patch('/dosen/pendaftar/'.$pendaftaran->id.'/status', [
            'status_seleksi' => 'ditolak',
        ]);

        $response->assertConflict();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
        ]);
    }

    public function test_student_must_meet_the_lowongan_grade_and_gpa_requirements(): void
    {
        Storage::fake('public');
        $lowongan = $this->createLowongan([
            'syarat_nilai' => 'B+',
            'syarat_ipk' => 3.5,
        ]);
        $applicationData = $this->validApplicationData($lowongan, [
            'nilai_matkul' => 'C',
            'ipk' => '3.20',
        ]);

        $response = $this->post('/mahasiswa/pendaftaran', $applicationData);

        $response->assertSessionHasErrors(['nilai_matkul', 'ipk']);
        $this->assertDatabaseCount('pendaftaran', 0);
    }

    public function test_dosen_cannot_approve_applicants_beyond_the_lowongan_quota(): void
    {
        $lowongan = $this->createLowongan(['kuota' => 1]);
        Pendaftaran::query()->create([
            'lowongan_id' => $lowongan->id,
            'nama' => 'Mahasiswa Terpilih',
            'nim' => '202600010',
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
            'is_published' => true,
        ]);
        $waiting = Pendaftaran::query()->create([
            'lowongan_id' => $lowongan->id,
            'nama' => 'Mahasiswa Menunggu',
            'nim' => '202600011',
            'status' => 'menunggu',
            'status_seleksi' => 'menunggu',
            'is_published' => false,
        ]);

        $response = $this->patch('/dosen/pendaftar/'.$waiting->id.'/status', [
            'status_seleksi' => 'disetujui',
        ]);

        $response->assertConflict();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $waiting->id,
            'status' => 'menunggu',
            'is_published' => false,
        ]);
    }

    public function test_mahasiswa_list_hides_private_dosen_notes(): void
    {
        $this->createPendaftaran([
            'catatan_dosen' => 'Catatan internal rahasia.',
            'is_published' => true,
        ]);

        $response = $this->getJson('/mahasiswa/pendaftar');

        $response->assertOk()
            ->assertJsonMissing(['catatan_dosen' => 'Catatan internal rahasia.']);
    }

    public function test_status_page_hides_unpublished_dosen_notes(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'catatan_dosen' => 'Catatan internal rahasia.',
            'is_published' => false,
        ]);

        $response = $this->withSession(['mahasiswa.pendaftaran_id' => $pendaftaran->id])
            ->get('/mahasiswa/status');

        $response->assertOk()
            ->assertSee('Menunggu keputusan dosen')
            ->assertDontSee('Catatan internal rahasia.');
    }

    public function test_status_page_displays_published_decision(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
            'catatan_dosen' => 'Selamat, pendaftaran disetujui.',
            'is_published' => true,
        ]);

        $response = $this->withSession(['mahasiswa.pendaftaran_id' => $pendaftaran->id])
            ->get('/mahasiswa/status');

        $response->assertOk()
            ->assertSee('Pendaftaran disetujui')
            ->assertSee('Selamat, pendaftaran disetujui.');
    }

    private function createLowongan(array $attributes = []): Pengumuman
    {
        return Pengumuman::query()->create(array_merge([
            'dosen_id' => 1,
            'mata_kuliah' => 'Algoritma Struktur Data',
            'kode' => '2403',
            'sks' => 3,
            'kuota' => 4,
            'jadwal' => 'Senin 08.00',
            'syarat' => 'Nilai minimum B.',
            'berkas_diminta' => json_encode(['CV', 'Transkrip nilai']),
            'tenggat' => now()->addWeek(),
            'status_aktif' => true,
        ], $attributes));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createPendaftaran(array $attributes = []): Pendaftaran
    {
        $lowongan = $this->createLowongan();

        return Pendaftaran::query()->create(array_merge([
            'lowongan_id' => $lowongan->id,
            'nama' => 'Mahasiswa Penguji',
            'nim' => '202600001',
            'nilai_matkul' => 'A',
            'ipk' => 3.75,
            'motivasi' => 'Saya tertarik membantu pelaksanaan praktikum.',
            'status' => 'menunggu',
            'status_seleksi' => 'menunggu',
            'is_published' => false,
            'catatan_dosen' => 'Mahasiswa memiliki performa akademik baik.',
        ], $attributes));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validApplicationData(Pengumuman $lowongan, array $overrides = []): array
    {
        return array_merge([
            'lowongan_id' => $lowongan->id,
            'nama' => 'Aldo Mahasiswa',
            'nim' => '202310101',
            'nilai_matkul' => 'A',
            'ipk' => '3.70',
            'motivasi' => 'Saya sangat tertarik dan memiliki kemampuan yang dibutuhkan untuk membantu praktikum.',
            'portfolio' => 'https://example.com/portfolio',
            'cv' => new UploadedFile(__DIR__.'/fixtures/sample.pdf', 'sample.pdf', 'application/pdf', null, true),
            'transkrip' => new UploadedFile(__DIR__.'/fixtures/sample.pdf', 'sample.pdf', 'application/pdf', null, true),
            'persetujuan' => '1',
        ], $overrides);
    }
}
