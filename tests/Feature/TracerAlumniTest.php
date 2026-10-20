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

    public function test_alumni_requires_nisn(): void
    {
        $resp = $this->actingAs($this->user())->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT X',
            'tahun_lulus' => 2023,
            'jurusan' => 'Teknik Pemesinan',
        ]);
        $resp->assertSessionHasErrors('nisn');
    }

    public function test_nisn_email_and_phone_are_stored(): void
    {
        $user = $this->user();

        $resp = $this->actingAs($user)->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT X',
            'tahun_lulus' => 2023,
            'jurusan' => 'Teknik Pemesinan',
            'nisn' => '3216547890',
            'email' => 'alumni@example.com',
            'no_wa' => '081234567890',
        ]);
        $resp->assertSessionHasNoErrors();

        $t = TracerStudy::where('user_id', $user->id)->first();
        $this->assertSame('3216547890', $t->nisn);
        $this->assertSame('alumni@example.com', $t->email);
        $this->assertSame('081234567890', $t->no_wa);
    }

    public function test_non_alumni_does_not_store_nisn(): void
    {
        $user = $this->user();

        $resp = $this->actingAs($user)->post(route('tracer.store'), [
            'is_alumni' => '0',
            'status_kerja' => 'kuliah',
            'company_name' => 'Univ Y',
            'asal_sekolah' => 'SMAN 1 Karawang',
            'nisn' => '3216547890',
            'email' => 'umum@example.com',
        ]);
        $resp->assertSessionHasNoErrors();

        $t = TracerStudy::where('user_id', $user->id)->first();
        $this->assertNull($t->nisn);
        $this->assertSame('umum@example.com', $t->email);
    }

    public function test_nisn_must_be_numeric(): void
    {
        $resp = $this->actingAs($this->user())->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT X',
            'tahun_lulus' => 2023,
            'jurusan' => 'Teknik Pemesinan',
            'nisn' => 'abc123',
        ]);
        $resp->assertSessionHasErrors('nisn');
    }

    public function test_nisn_cannot_be_used_by_another_account(): void
    {
        $first = $this->user();
        $this->actingAs($first)->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT X',
            'tahun_lulus' => 2023,
            'jurusan' => 'Teknik Pemesinan',
            'nisn' => '3216547890',
        ])->assertSessionHasNoErrors();

        // Akun lain memakai NISN yang sama → ditolak.
        $second = $this->user();
        $this->actingAs($second)->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT Y',
            'tahun_lulus' => 2023,
            'jurusan' => 'Teknik Pemesinan',
            'nisn' => '3216547890',
        ])->assertSessionHasErrors('nisn');

        // Akun yang sama boleh memperbarui form-nya sendiri.
        $this->actingAs($first)->post(route('tracer.store'), [
            'is_alumni' => '1',
            'status_kerja' => 'bekerja',
            'company_name' => 'PT X',
            'tahun_lulus' => 2024,
            'jurusan' => 'Teknik Pemesinan',
            'nisn' => '3216547890',
        ])->assertSessionHasNoErrors();
    }

    public function test_email_must_be_valid(): void
    {
        $resp = $this->actingAs($this->user())->post(route('tracer.store'), [
            'is_alumni' => '0',
            'status_kerja' => 'kuliah',
            'company_name' => 'Univ Y',
            'asal_sekolah' => 'SMAN 1 Karawang',
            'email' => 'bukan-email',
        ]);
        $resp->assertSessionHasErrors('email');
    }

    public function test_tracer_form_shows_nisn_email_and_phone_inputs(): void
    {
        $resp = $this->actingAs($this->user())->get(route('tracer.index'));

        $resp->assertStatus(200);
        $resp->assertSee('name="nisn"', false);
        $resp->assertSee('name="email"', false);
        $resp->assertSee('name="no_wa"', false);
        $resp->assertSee('No. HP / WhatsApp', false);
    }
}
