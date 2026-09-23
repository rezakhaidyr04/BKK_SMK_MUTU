<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * P5.5: API / public access & privacy edge cases (TEST-ONLY).
 *
 * Follows existing production behavior as source of truth.
 * Does not duplicate P5.1–P5.4, ApiTokenTest (token creation + basic
 * /api/user auth + draft excluded from index), or JobGuardTest
 * (company allowlist/denylist on /api/jobs list + detail subset).
 *
 * Audit map (endpoint → auth → output → gap):
 * - GET /api/jobs            → public → JobResource[] + meta → structure/meta contract, closed excluded
 * - GET /api/jobs/{job}      → public → JobResource / 404 if status != active → non-active/missing/trashed 404
 * - GET /api/user            → auth:sanctum → User JSON → guest 401, no credential leak, expired/revoked 401
 * - POST admin/api-tokens    → role:admin → scoped expiring token, show-once → abilities/expiry/hash contract, non-admin 403
 * - DELETE admin/api-tokens/{id} → role:admin, owner-scoped → own revoke, cross-user 404
 * - Note: no route enforces tokenCan(); abilities are issuance-only (documented in controller comment).
 * - Note: API index/show filter by status only (no deadline guard unlike web); expired-job visibility
 *   is reported as DEFERRED and intentionally NOT locked by a test here.
 */
class ApiPrivacyTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $over = []): User
    {
        $admin = User::factory()->create(array_merge(['role' => 'admin'], $over));
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        return $admin;
    }

    // ---------- Public jobs index contract ----------

    public function test_public_jobs_index_returns_data_meta_structure(): void
    {
        Job::factory()->create(['status' => 'active', 'title' => 'Struktur Aktif']);

        $response = $this->getJson('/api/jobs');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                ['id', 'company_id', 'company_name', 'title', 'position', 'location', 'status', 'company'],
            ],
            'meta' => ['current_page', 'last_page', 'total'],
        ]);
        $response->assertJsonFragment(['title' => 'Struktur Aktif']);
    }

    public function test_public_jobs_index_excludes_closed_jobs(): void
    {
        Job::factory()->create(['status' => 'active', 'title' => 'Lowongan Buka']);
        Job::factory()->create(['status' => 'closed', 'title' => 'Lowongan Tutup']);

        $response = $this->getJson('/api/jobs');

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Lowongan Buka']);
        $response->assertJsonMissing(['title' => 'Lowongan Tutup']);
    }

    // ---------- Public job detail edge cases ----------

    public function test_public_job_show_returns_404_for_non_active(): void
    {
        $draft = Job::factory()->create(['status' => 'draft', 'deadline' => now()->addWeek()]);
        $closed = Job::factory()->create(['status' => 'closed', 'deadline' => now()->addWeek()]);

        $this->getJson('/api/jobs/'.$draft->id)
            ->assertNotFound()
            ->assertJson(['message' => 'Lowongan tidak ditemukan.']);

        $this->getJson('/api/jobs/'.$closed->id)
            ->assertNotFound()
            ->assertJson(['message' => 'Lowongan tidak ditemukan.']);
    }

    public function test_public_job_show_returns_404_for_missing_or_deleted(): void
    {
        $this->getJson('/api/jobs/999999')->assertNotFound();

        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);
        $job->delete(); // soft delete

        $this->getJson('/api/jobs/'.$job->id)->assertNotFound();
    }

    public function test_public_job_show_returns_job_with_public_company(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek(), 'title' => 'Detail Aktif']);

        $response = $this->getJson('/api/jobs/'.$job->id);

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Detail Aktif']);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'status', 'company' => ['id', 'name']],
        ]);
    }

    // ---------- P5.6: expiry consistency (ACTIVE + not expired = public) ----------

    public function test_public_jobs_index_excludes_expired_active_jobs(): void
    {
        Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek(), 'title' => 'API Future Aktif']);
        Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay(), 'title' => 'API Expired Aktif']);

        $response = $this->getJson('/api/jobs');

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'API Future Aktif']);
        $response->assertJsonMissing(['title' => 'API Expired Aktif']);
    }

    public function test_public_job_show_rejects_expired_active_job(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);

        $this->getJson('/api/jobs/'.$job->id)
            ->assertNotFound()
            ->assertJson(['message' => 'Lowongan tidak ditemukan.']);
    }

    public function test_public_job_show_allows_active_non_expired_job(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek(), 'title' => 'API Show Aktif']);

        $response = $this->getJson('/api/jobs/'.$job->id);

        $response->assertOk();
        $response->assertJsonPath('data.id', $job->id);
        $response->assertJsonFragment(['title' => 'API Show Aktif']);
    }

    public function test_public_jobs_expiry_boundary_uses_deadline_gte_now(): void
    {
        // Pin the clock so the boundary is deterministic (no sleep()).
        // Evidence: jobs.deadline is a DATE column, so the effective
        // boundary is day-granularity: a deadline date of today is already
        // past (>= now() fails past midnight), tomorrow is still public.
        // This mirrors the web index predicate exactly.
        $frozen = \Carbon\Carbon::parse('2026-06-15 12:00:00');
        $this->travelTo($frozen);

        try {
            $tomorrow = Job::factory()->create([
                'status' => 'active',
                'deadline' => $frozen->copy()->addDay()->toDateString(),
                'title' => 'API Boundary Besok',
            ]);
            $today = Job::factory()->create([
                'status' => 'active',
                'deadline' => $frozen->copy()->toDateString(),
                'title' => 'API Boundary Hari Ini',
            ]);

            $index = $this->getJson('/api/jobs');
            $index->assertOk();
            $index->assertJsonFragment(['title' => 'API Boundary Besok']);
            $index->assertJsonMissing(['title' => 'API Boundary Hari Ini']);

            $this->getJson('/api/jobs/'.$tomorrow->id)->assertOk();
            $this->getJson('/api/jobs/'.$today->id)->assertNotFound();
        } finally {
            $this->travelBack();
        }
    }

    // ---------- /api/user auth + privacy ----------

    public function test_api_user_requires_authentication(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_api_user_does_not_leak_credentials(): void
    {
        $admin = $this->admin();
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertOk();
        $response->assertJson(['id' => $admin->id]);
        $response->assertJsonMissing(['password']);
        $response->assertJsonMissing(['remember_token']);
    }

    public function test_api_user_rejects_expired_token(): void
    {
        $admin = $this->admin();
        $token = $admin->createToken('expired-token', ['jobs:read'], now()->subMinute())->plainTextToken;

        $this->withToken($token)->getJson('/api/user')->assertUnauthorized();
    }

    public function test_api_user_rejects_revoked_token(): void
    {
        $admin = $this->admin();
        $token = $admin->createToken('revoked-token')->plainTextToken;
        $admin->tokens()->delete();

        $this->withToken($token)->getJson('/api/user')->assertUnauthorized();
    }

    public function test_jobs_read_token_authenticates_auth_only_endpoint(): void
    {
        // Existing behavior: abilities are issuance-only; /api/user gates on
        // token validity (auth:sanctum), not on tokenCan(). Documents the
        // controller's documented intent ("/api/user butuh auth (token valid)").
        $admin = $this->admin();
        $token = $admin->createToken('scoped-token', ['jobs:read'])->plainTextToken;

        $this->withToken($token)->getJson('/api/user')->assertOk();
    }

    // ---------- Admin token lifecycle ----------

    public function test_admin_token_creation_persists_scoped_expiring_token(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.api-tokens.store'), [
            'token_name' => 'audit-scope',
        ]);

        $response->assertRedirect(route('admin.api-tokens.index'));
        $response->assertSessionHas('api_token');

        $plain = $response->getSession()->get('api_token');
        $stored = $admin->tokens()->where('name', 'audit-scope')->firstOrFail();

        // Ability contract: jobs:read only, never [*].
        $this->assertSame(['jobs:read'], $stored->abilities);
        // Expiry contract: ~30 days.
        $this->assertNotNull($stored->expires_at);
        $this->assertTrue(
            $stored->expires_at->greaterThan(now()->addDays(29))
                && $stored->expires_at->lessThan(now()->addDays(31))
        );
        // Show-once contract: only a hash is persisted, never the plaintext.
        $this->assertNotSame($plain, $stored->getRawOriginal('token'));
    }

    public function test_admin_can_revoke_own_token(): void
    {
        $admin = $this->admin();
        $admin->createToken('to-revoke');
        $tokenId = $admin->tokens()->firstOrFail()->id;

        $this->actingAs($admin)
            ->delete(route('admin.api-tokens.destroy', $tokenId))
            ->assertRedirect(route('admin.api-tokens.index'));

        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
    }

    public function test_admin_cannot_revoke_another_admin_token(): void
    {
        $owner = $this->admin();
        $other = $this->admin();
        $owner->createToken('owner-token');
        $tokenId = $owner->tokens()->firstOrFail()->id;

        // Owner-scoped lookup → 404 for another admin's token; token stays intact.
        $this->actingAs($other)
            ->delete(route('admin.api-tokens.destroy', $tokenId))
            ->assertNotFound();

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $tokenId]);
    }

    public function test_non_admin_cannot_manage_api_tokens(): void
    {
        $umum = User::factory()->create(['role' => 'umum']);

        $this->actingAs($umum)->get(route('admin.api-tokens.index'))->assertForbidden();
        $this->actingAs($umum)->post(route('admin.api-tokens.store'), [
            'token_name' => 'nope',
        ])->assertForbidden();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
