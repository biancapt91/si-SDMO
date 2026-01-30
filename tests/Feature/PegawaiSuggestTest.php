<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Pegawai;

class PegawaiSuggestTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggest_returns_matching_pegawai()
    {
        Pegawai::factory()->create(['nip' => '123', 'nama' => 'Budi']);
        Pegawai::factory()->create(['nip' => '456', 'nama' => 'Siti']);

        $response = $this->getJson(route('pegawai.suggest', ['q' => 'Bu']));

        $response->assertStatus(200);
        $response->assertJsonFragment(['nip' => '123', 'nama' => 'Budi']);
        $this->assertCount(1, $response->json());
    }
}
