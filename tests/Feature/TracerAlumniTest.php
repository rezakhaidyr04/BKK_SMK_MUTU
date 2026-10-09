<?php

namespace Tests\Feature;

use App\Models\TracerStudy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TracerAlumniTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        $u = User::factory()->create(['role' => 'umum']);
        $u->forceFill(['email_verified_at' => now()])->save();
        return $u;
    }

    public function test_alumni_requires_jurusan_and_tahun(): void
    {
        $resp = $this->actingAs($this->user())->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT X',
        ]);
        $resp->assertSessionHasErrors(['tahun_lulus', 'jurusan']);
    }

    public function test_non_alumni_requires_asal_and_nulls_jurusan(): void
    {
        $user = $this->user();
        $resp = $this->actingAs($user)->post(route('tracer.store'), [
            'is_alumni' => '0',
            'status_kerja' => 'kuliah',
            'company_name' => 'Univ Y',
        ]);
        $resp->assertSessionHasErrors('asal_sekolah');

        $resp = $this->actingAs($user)->post(route('tracer.store'), [
            'is_alumni' => '0',
            'status_kerja' => 'kuliah',
            'company_name' => 'Univ Y',
            'asal_sekolah' => 'SMAN 1 Karawang',
            'jurusan' => 'Coba',
        ]);
        $resp->assertSessionHasNoErrors();
        $t = TracerStudy::where('user_id', $user->id)->first();
        $this->assertFalse((bool) $t->is_alumni);
        $this->assertNull($t->jurusan);
        $this->assertSame('SMAN 1 Karawang', $t->asal_sekolah);
    }
}
