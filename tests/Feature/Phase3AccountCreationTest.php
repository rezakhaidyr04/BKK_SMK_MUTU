<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * PHASE 3 — Account Creation Tests
 *
 * Ketentuan:
 * - Hanya perusahaan APPROVED yang boleh dibuatkan akun
 * - Tidak boleh duplikat akun
 * - Email harus unik di tabel users
 * - Password disimpan dalam bentuk hash
 * - must_change_password = true
 * - users.role = company & Spatie role = company
 */
class Phase3AccountCreationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'company',  'guard_name' => 'web']);

        $this->admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);
        $this->admin->assignRole('admin');
    }

    // ─────────────────────────────────────────────────────────────
    // HAPPY PATH
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_create_account_for_approved_company(): void
    {
        $company = Company::factory()->withoutAccount()->approved()->create([
            'name'  => 'PT Maju Test',
            'email' => 'hrd@majutest.com',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'hrd@majutest.com']
        );

        $response->assertRedirect(route('admin.companies.show', $company));
        $response->assertSessionHas('account_created', true);
        $response->assertSessionHas('temp_password');  // password sementara di flash
        $response->assertSessionHas('account_email', 'hrd@majutest.com');

        // User harus dibuat
        $this->assertDatabaseHas('users', [
            'email' => 'hrd@majutest.com',
            'role'  => 'company',
            'must_change_password' => true,
        ]);

        // Perusahaan harus terhubung ke user
        $company->refresh();
        $this->assertNotNull($company->user_id);

        $user = $company->user;
        $this->assertNotNull($user);
        $this->assertTrue($user->must_change_password);
    }

    /** @test */
    public function created_user_has_hashed_password_not_plaintext(): void
    {
        $company = Company::factory()->withoutAccount()->approved()->create();

        $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'test@company.com']
        );

        $user = User::where('email', 'test@company.com')->first();
        $this->assertNotNull($user);

        // Password harus dalam bentuk hash (bcrypt/argon2)
        $this->assertStringStartsWith('$', $user->getRawOriginal('password'));

        // Password BUKAN plaintext biasa
        $this->assertNotEquals('test@company.com', $user->password);
        $this->assertGreaterThan(20, strlen($user->getRawOriginal('password')));
    }

    /** @test */
    public function created_user_has_spatie_company_role(): void
    {
        $company = Company::factory()->withoutAccount()->approved()->create();

        $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'spatie@test.com']
        );

        $user = User::where('email', 'spatie@test.com')->first();
        $this->assertNotNull($user);

        // Harus punya KEDUA: users.role = company DAN Spatie role = company
        $this->assertEquals('company', $user->role);
        $this->assertTrue($user->hasRole('company'));
    }

    /** @test */
    public function created_user_must_change_password_is_true(): void
    {
        $company = Company::factory()->withoutAccount()->approved()->create();

        $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'mustchange@test.com']
        );

        $user = User::where('email', 'mustchange@test.com')->first();
        $this->assertTrue((bool) $user->must_change_password);
    }

    // ─────────────────────────────────────────────────────────────
    // GUARD RAILS
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function cannot_create_account_for_pending_company(): void
    {
        $company = Company::factory()->withoutAccount()->create([
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'notallowed@test.com']
        );

        $response->assertRedirect(route('admin.companies.show', $company));
        $response->assertSessionHas('error');

        // Tidak boleh ada user dibuat
        $this->assertDatabaseMissing('users', ['email' => 'notallowed@test.com']);
    }

    /** @test */
    public function cannot_create_account_for_rejected_company(): void
    {
        $company = Company::factory()->withoutAccount()->create([
            'verification_status' => 'rejected',
        ]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'rejected@test.com']
        );

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('users', ['email' => 'rejected@test.com']);
    }

    /** @test */
    public function cannot_create_duplicate_account_for_approved_company(): void
    {
        $existingUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->approved()->create(['user_id' => $existingUser->id]);

        $response = $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'another@test.com']
        );

        $response->assertRedirect(route('admin.companies.show', $company));
        $response->assertSessionHas('error');

        // user_id tidak boleh berubah
        $company->refresh();
        $this->assertEquals($existingUser->id, $company->user_id);
    }

    /** @test */
    public function email_must_be_unique_across_users_table(): void
    {
        // Buat user lain dengan email yang sama
        User::factory()->create(['email' => 'existing@email.com']);

        $company = Company::factory()->withoutAccount()->approved()->create();

        $response = $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'existing@email.com']
        );

        $response->assertSessionHasErrors('email');

        // Perusahaan tidak boleh punya user_id
        $company->refresh();
        $this->assertNull($company->user_id);
    }

    /** @test */
    public function email_field_is_required(): void
    {
        $company = Company::factory()->withoutAccount()->approved()->create();

        $response = $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => '']
        );

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function create_account_route_requires_admin(): void
    {
        $company  = Company::factory()->withoutAccount()->approved()->create();
        $student  = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'hrd@test.com']
        );

        $response->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => 'hrd@test.com']);
    }

    /** @test */
    public function temp_password_is_shown_only_once_in_session(): void
    {
        $company = Company::factory()->withoutAccount()->approved()->create();

        // First request — buat akun
        $this->actingAs($this->admin)->post(
            route('admin.companies.create-account', $company),
            ['email' => 'onetime@test.com']
        );

        // Akses show page pertama kali — password ada di flash
        $firstVisit = $this->actingAs($this->admin)->get(
            route('admin.companies.show', $company)
        );
        $firstVisit->assertSessionMissing('temp_password'); // Sudah di-consume oleh flash
    }
}
