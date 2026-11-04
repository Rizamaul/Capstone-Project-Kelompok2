<?php

namespace Tests\Feature;

use Tests\TestCase;

class DosenLowonganControllerTest extends TestCase
{
    public function test_dosen_lowongan_index_returns_announcements(): void
    {
        $response = $this->getJson('/dosen/lowongan');

        $response->assertOk()
            ->assertJsonStructure([
                ['id', 'nama', 'kode', 'status'],
            ]);
    }

    public function test_dosen_lowongan_can_toggle_status(): void
    {
        $response = $this->patchJson('/dosen/lowongan/1/status', [
            'status' => 'tutup',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'tutup');

        $this->patchJson('/dosen/lowongan/1/status', [
            'status' => 'aktif',
        ])->assertOk()
            ->assertJsonPath('data.status', 'aktif');
    }
}
