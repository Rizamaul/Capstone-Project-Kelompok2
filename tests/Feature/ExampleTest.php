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
}
