<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Tests\TestCase;

/**
 * PHASE 1 — Database & Security Foundation Tests
 *
 * Verifies:
 * - companies.user_id is nullable
 * - companies.mou_path, mou_number, mou_signed_at, mou_expires_at exist
 * - companies.reviewed_by, reviewed_at exist
 * - companies.rejection_reason exists
 * - companies legacy columns NOT removed
 * - users.must_change_password exists with default false
 * - users.password_changed_at exists as nullable
 * - Spatie role 'company' exists
 * - All users with role='company' have Spatie 'company' role
 *
 * NOTE: Tidak menggunakan RefreshDatabase karena menguji schema live.
 * Tapi memiliki setUp() untuk memastikan Spatie roles ada.
 */
class Phase1DatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan Spatie roles tersedia di test DB (tidak menghapus data lain)
        Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'teacher',  'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student',  'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'alumni',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'company',  'guard_name' => 'web']);
    }

    /** @test */
    public function companies_user_id_is_nullable(): void
    {
        $colInfo = DB::select(
            "SELECT IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = 'companies'
             AND COLUMN_NAME = 'user_id'"
        );

        $this->assertNotEmpty($colInfo, 'companies.user_id column not found');
        $this->assertEquals('YES', $colInfo[0]->IS_NULLABLE,
            'companies.user_id should be nullable');
    }

    /** @test */
    public function companies_user_id_has_set_null_on_delete(): void
    {
        $fks = DB::select("
            SELECT rc.DELETE_RULE
            FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
            JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
                ON rc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
                AND rc.CONSTRAINT_SCHEMA = kcu.TABLE_SCHEMA
            WHERE kcu.TABLE_NAME = 'companies'
            AND kcu.COLUMN_NAME = 'user_id'
            AND kcu.TABLE_SCHEMA = DATABASE()
        ");

        $this->assertNotEmpty($fks, 'No FK found for companies.user_id');
        $this->assertEquals('SET NULL', $fks[0]->DELETE_RULE,
            'companies.user_id FK should be SET NULL on delete');
    }

    /** @test */
    public function companies_mou_columns_exist(): void
    {
        $columns = ['mou_path', 'mou_number', 'mou_signed_at', 'mou_expires_at'];
        foreach ($columns as $col) {
            $this->assertTrue(
                Schema::hasColumn('companies', $col),
                "companies.$col should exist"
            );
        }
    }

    /** @test */
    public function companies_review_columns_exist(): void
    {
        $columns = ['reviewed_by', 'reviewed_at', 'rejection_reason'];
        foreach ($columns as $col) {
            $this->assertTrue(
                Schema::hasColumn('companies', $col),
                "companies.$col should exist"
            );
        }
    }

    /** @test */
    public function companies_legacy_columns_not_removed(): void
    {
        $legacyColumns = [
            'business_license_path',
            'operating_license_path',
            'npwp_path',
            'tax_number',
            'is_verified',
            'verification_status',
        ];

        foreach ($legacyColumns as $col) {
            $this->assertTrue(
                Schema::hasColumn('companies', $col),
                "Legacy column companies.$col should NOT be removed"
            );
        }
    }

    /** @test */
    public function users_must_change_password_column_exists_with_default_false(): void
    {
        $this->assertTrue(
            Schema::hasColumn('users', 'must_change_password'),
            'users.must_change_password should exist'
        );

        $colInfo = DB::select(
            "SELECT COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = 'users'
             AND COLUMN_NAME = 'must_change_password'"
        );

        $this->assertNotEmpty($colInfo);
        $this->assertEquals('0', $colInfo[0]->COLUMN_DEFAULT,
            'must_change_password should default to 0 (false)');
    }

    /** @test */
    public function users_password_changed_at_column_is_nullable(): void
    {
        $this->assertTrue(
            Schema::hasColumn('users', 'password_changed_at'),
            'users.password_changed_at should exist'
        );

        $colInfo = DB::select(
            "SELECT IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = 'users'
             AND COLUMN_NAME = 'password_changed_at'"
        );

        $this->assertNotEmpty($colInfo);
        $this->assertEquals('YES', $colInfo[0]->IS_NULLABLE,
            'users.password_changed_at should be nullable');
    }

    /** @test */
    public function spatie_company_role_exists(): void
    {
        $role = Role::where('name', 'company')->where('guard_name', 'web')->first();
        $this->assertNotNull($role, "Spatie role 'company' (guard: web) should exist");
    }

    /** @test */
    public function all_company_users_have_spatie_company_role(): void
    {
        $companyUsers = User::where('role', 'company')->get();

        $unsynced = $companyUsers->filter(fn($u) => !$u->hasRole('company'));

        $this->assertTrue(
            $unsynced->isEmpty(),
            'These company users are missing Spatie role: ' .
            $unsynced->pluck('email')->implode(', ')
        );
    }

    /** @test */
    public function user_model_fillable_has_must_change_password(): void
    {
        $user = new User();
        $this->assertContains('must_change_password', $user->getFillable(),
            'User model should have must_change_password in fillable');
        $this->assertContains('password_changed_at', $user->getFillable(),
            'User model should have password_changed_at in fillable');
    }

    /** @test */
    public function company_model_fillable_has_mou_fields(): void
    {
        $company = new \App\Models\Company();
        $fillable = $company->getFillable();

        $required = ['mou_path', 'mou_number', 'mou_signed_at', 'mou_expires_at',
                     'reviewed_by', 'reviewed_at'];

        foreach ($required as $field) {
            $this->assertContains($field, $fillable,
                "Company model should have $field in fillable");
        }
    }
}
