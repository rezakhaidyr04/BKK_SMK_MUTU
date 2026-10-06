<?php

namespace Tests\Feature;

use App\Services\CvBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CvGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_generate_endpoint_creates_cv_file(): void
    {
        Storage::fake('private');

        $user = \App\Models\User::factory()->create(['role' => 'umum']);
        $user->skills()->create(['name' => 'PHP']);

        $response = $this->actingAs($user)
            ->post(route('cv.generate'), [
                'include_skills' => true,
                'include_certificates' => false,
            ]);

        $response->assertRedirect();
        // M9: async — response langsung kembali (pesan proses), worker
        // (sync driver di test) menyelesaikan PDF dalam request yang sama.
        $response->assertSessionHas('success', 'CV sedang diproses di background. Halaman akan dimuat ulang otomatis.');
        $this->assertDatabaseHas('cv_files', ['user_id' => $user->id]);
    }

    public function test_cv_builder_service_creates_cv_file(): void
    {
        Storage::fake('private');

        $user = \App\Models\User::factory()->create(['role' => 'umum']);
        $this->actingAs($user);

        (new CvBuilderService())->generateCv([
            'include_skills' => true,
            'include_certificates' => true,
        ]);

        $this->assertDatabaseHas('cv_files', ['user_id' => $user->id]);
    }
}
