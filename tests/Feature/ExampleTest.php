<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_homepage_displays_the_asistin_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Semua Langkah, Satu Ruang Kerja.');
    }
}
