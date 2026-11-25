<?php

namespace Tests\Feature;

use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Models\User;
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
            ->assertSee('Mahasiswa memiliki performa akademik baik')
            ->assertDontSee('Peninjauan Berkas Lengkap');
    }

    public function test_dosen_can_view_pendaftaran_show_page_with_details_and_pdf(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'cv_path' => 'pendaftaran/cv/sample.pdf',
            'transkrip_path' => 'pendaftaran/transkrip/sample.pdf',
        ]);

        $response = $this->get('/dosen/pendaftar/'.$pendaftaran->id);

        $response->assertOk()
            ->assertSee($pendaftaran->nama)
            ->assertSee($pendaftaran->nim)
            ->assertSee('Pratinjau Berkas Pelamar')
            ->assertSee('Curriculum Vitae (CV)')
            ->assertSee('Transkrip Nilai / KSM');
    }

    public function test_dosen_must_make_a_final_decision_after_the_interview_passes(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
            'jadwal_wawancara' => 'Wawancara selesai',
            'waktu_wawancara' => now()->subHour(),
        ]);

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/hasil-wawancara', [
            'hasil_wawancara' => 'lulus',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'menunggu',
            'status_seleksi' => 'menunggu',
            'hasil_wawancara' => 'lulus',
            'is_published' => false,
        ]);

        $finalResponse = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/keputusan-akhir', [
            'status_seleksi' => 'disetujui',
        ]);

        $finalResponse->assertRedirect();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
            'hasil_wawancara' => 'lulus',
            'is_published' => true,
        ]);
    }

    public function test_dosen_cannot_publish_a_second_decision(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status' => 'disetujui',
            'status_seleksi' => 'disetujui',
            'hasil_wawancara' => 'lulus',
            'is_published' => true,
        ]);

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/hasil-wawancara', [
            'hasil_wawancara' => 'gagal',
            'catatan_hasil_wawancara' => 'Tidak memenuhi kriteria wawancara.',
        ]);

        $response->assertConflict();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'disetujui',
            'hasil_wawancara' => 'lulus',
        ]);
    }

    public function test_dosen_can_reject_at_the_final_decision_after_a_successful_interview(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
            'jadwal_wawancara' => 'Wawancara selesai',
            'hasil_wawancara' => 'lulus',
        ]);

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/keputusan-akhir', [
            'status_seleksi' => 'ditolak',
            'catatan_dosen' => 'Kuota penempatan untuk periode ini sudah terpenuhi.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'hasil_wawancara' => 'lulus',
            'status' => 'ditolak',
            'status_seleksi' => 'ditolak',
            'is_published' => true,
        ]);
    }

    public function test_dosen_cannot_publish_an_application_through_the_old_direct_status_route(): void
    {
        $pendaftaran = $this->createPendaftaran();

        $this->patch('/dosen/pendaftar/'.$pendaftaran->id.'/status', [
            'status_seleksi' => 'disetujui',
        ])->assertNotFound();

        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'menunggu',
            'is_published' => false,
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

    public function test_dosen_cannot_accept_applicants_beyond_the_lowongan_quota(): void
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
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
            'jadwal_wawancara' => 'Wawancara selesai',
            'waktu_wawancara' => now()->subHour(),
            'is_published' => false,
        ]);

        $this->post('/dosen/pendaftar/'.$waiting->id.'/hasil-wawancara', [
            'hasil_wawancara' => 'lulus',
        ])->assertRedirect();

        $response = $this->post('/dosen/pendaftar/'.$waiting->id.'/keputusan-akhir', [
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

    public function test_dosen_can_verify_berkas_and_it_shows_green_in_student_status(): void
    {
        $pendaftaran = $this->createPendaftaran();

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/verifikasi-berkas', [
            'status' => 'diverifikasi',
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'berkas_diverifikasi' => true,
            'status_berkas' => 'diverifikasi',
        ]);

        $studentResponse = $this->get('/mahasiswa/status/'.$pendaftaran->id);
        $studentResponse->assertOk()
            ->assertSee('Dosen telah meninjau berkas pendaftaran.');
    }

    public function test_dosen_can_reject_berkas_with_reason_and_shows_in_student_status(): void
    {
        $pendaftaran = $this->createPendaftaran();

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/verifikasi-berkas', [
            'status' => 'ditolak',
            'alasan' => 'Format transkrip nilai belum memuat semester terbaru.',
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'berkas_diverifikasi' => false,
            'status_berkas' => 'ditolak',
            'catatan_berkas' => 'Format transkrip nilai belum memuat semester terbaru.',
            'status' => 'ditolak',
            'status_seleksi' => 'ditolak',
            'is_published' => true,
        ]);

        $studentResponse = $this->get('/mahasiswa/status/'.$pendaftaran->id);
        $studentResponse->assertOk()
            ->assertSee('Format transkrip nilai belum memuat semester terbaru.')
            ->assertSee('Kekurangan / Penolakan Berkas')
            ->assertSee('aria-label="Tahap gagal"', false)
            ->assertSee('aria-label="Tahap tidak dilanjutkan"', false);

        $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/verifikasi-berkas', [
            'status' => 'diverifikasi',
        ])->assertConflict();
    }

    public function test_interview_cannot_be_scheduled_before_documents_are_verified(): void
    {
        $pendaftaran = $this->createPendaftaran();

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/wawancara', [
            'metode_wawancara' => 'langsung',
            'waktu_wawancara' => now()->addDay()->format('Y-m-d\TH:i'),
            'lokasi_wawancara' => 'Lab Komputer 2',
            'catatan_wawancara' => 'Bawa kartu mahasiswa dan hadir sepuluh menit lebih awal.',
        ]);

        $response->assertConflict();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'jadwal_wawancara' => null,
            'status' => 'menunggu',
            'is_published' => false,
        ]);
    }

    public function test_scheduling_an_interview_does_not_decide_the_application(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
        ]);

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/wawancara', [
            'metode_wawancara' => 'online',
            'waktu_wawancara' => now()->addDay()->format('Y-m-d\TH:i'),
            'lokasi_wawancara' => 'https://meet.example.com/applicant',
            'catatan_wawancara' => 'Siapkan koneksi internet stabil dan bergabung lima menit lebih awal.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'metode_wawancara' => 'online',
            'lokasi_wawancara' => 'https://meet.example.com/applicant',
            'catatan_wawancara' => 'Siapkan koneksi internet stabil dan bergabung lima menit lebih awal.',
            'berkas_diverifikasi' => true,
            'status' => 'menunggu',
            'is_published' => false,
        ]);

        $studentResponse = $this->get('/mahasiswa/status/'.$pendaftaran->id);
        $studentResponse->assertOk()
            ->assertSee('Notifikasi Wawancara')
            ->assertSee('https://meet.example.com/applicant')
            ->assertSee('Siapkan koneksi internet stabil dan bergabung lima menit lebih awal.')
            ->assertSee('Daring')
            ->assertSee('aria-label="Notifikasi wawancara"', false);

        $this->get('/dosen/pendaftar/'.$pendaftaran->id)
            ->assertOk()
            ->assertSee('Edit data wawancara')
            ->assertSee('x-show="editInterview"', false);
    }

    public function test_dosen_must_add_a_reason_when_interview_fails(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
            'jadwal_wawancara' => 'Wawancara selesai',
            'waktu_wawancara' => now()->subHour(),
        ]);

        $response = $this->from('/dosen/pendaftar/'.$pendaftaran->id)->post(
            '/dosen/pendaftar/'.$pendaftaran->id.'/hasil-wawancara',
            ['hasil_wawancara' => 'gagal']
        );

        $response->assertSessionHasErrors('catatan_hasil_wawancara');
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'menunggu',
            'is_published' => false,
            'hasil_wawancara' => null,
        ]);
    }

    public function test_interview_result_can_be_saved_immediately_after_scheduling(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
            'jadwal_wawancara' => 'Wawancara mendatang',
            'waktu_wawancara' => now()->addDay(),
        ]);

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/hasil-wawancara', [
            'hasil_wawancara' => 'lulus',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'status' => 'menunggu',
            'is_published' => false,
            'hasil_wawancara' => 'lulus',
        ]);
    }

    public function test_interview_failure_rejects_the_whole_application(): void
    {
        $pendaftaran = $this->createPendaftaran([
            'status_berkas' => 'diverifikasi',
            'berkas_diverifikasi' => true,
            'jadwal_wawancara' => 'Wawancara selesai',
            'waktu_wawancara' => now()->subHour(),
        ]);

        $response = $this->post('/dosen/pendaftar/'.$pendaftaran->id.'/hasil-wawancara', [
            'hasil_wawancara' => 'gagal',
            'catatan_hasil_wawancara' => 'Jawaban belum memenuhi kriteria teknis.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $pendaftaran->id,
            'hasil_wawancara' => 'gagal',
            'status' => 'ditolak',
            'status_seleksi' => 'ditolak',
            'is_published' => true,
        ]);

        $this->get('/mahasiswa/status/'.$pendaftaran->id)
            ->assertOk()
            ->assertSee('aria-label="Tahap gagal"', false)
            ->assertSee('aria-label="Tahap tidak dilanjutkan"', false);
    }

    public function test_dosen_can_see_the_applicants_account_email(): void
    {
        $user = User::factory()->create(['name' => 'Aldo Mahasiswa', 'email' => 'aldo@example.test']);
        $pendaftaran = $this->createPendaftaran(['user_id' => $user->id]);

        $this->get('/dosen/pendaftar/'.$pendaftaran->id)
            ->assertOk()
            ->assertSee('aldo@example.test')
            ->assertSee('mailto:aldo@example.test', false);
    }

    public function test_dosen_can_stream_and_download_applicant_pdf_via_controller_route(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('pendaftaran/cv/test-cv.pdf', '%PDF-1.4 fake cv content');
        Storage::disk('public')->put('pendaftaran/transkrip/test-transkrip.pdf', '%PDF-1.4 fake transkrip content');

        $pendaftaran = $this->createPendaftaran([
            'cv_path' => 'pendaftaran/cv/test-cv.pdf',
            'transkrip_path' => 'pendaftaran/transkrip/test-transkrip.pdf',
        ]);

        $this->get('/dosen/pendaftar/'.$pendaftaran->id)
            ->assertOk()
            ->assertSee(route('dosen.pendaftar.berkas', ['pendaftaran' => $pendaftaran, 'jenis' => 'cv']))
            ->assertSee(route('dosen.pendaftar.berkas', ['pendaftaran' => $pendaftaran, 'jenis' => 'transkrip']));

        $this->get('/dosen/pendaftar/'.$pendaftaran->id.'/berkas/cv')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->get('/dosen/pendaftar/'.$pendaftaran->id.'/berkas/transkrip?download=1')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
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
            'cv' => UploadedFile::fake()->create('sample.pdf', 100, 'application/pdf'),
            'transkrip' => UploadedFile::fake()->create('sample.pdf', 100, 'application/pdf'),
            'persetujuan' => '1',
        ], $overrides);
    }
}
