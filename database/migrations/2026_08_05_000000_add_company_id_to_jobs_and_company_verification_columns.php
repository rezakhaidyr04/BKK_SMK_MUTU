<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('jobs', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
        });

        DB::table('jobs as j')
            ->join('companies as c', 'j.company_name', '=', 'c.name')
            ->whereNotNull('j.company_name')
            ->update(['company_id' => DB::raw('c.id')]);

        Schema::table('companies', function (Blueprint $table) {
            if (! Schema::hasColumn('companies', 'email')) {
                $table->string('email')->nullable()->after('website');
            }
            if (! Schema::hasColumn('companies', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (! Schema::hasColumn('companies', 'tax_number')) {
                $table->string('tax_number')->nullable()->after('address');
            }
            if (! Schema::hasColumn('companies', 'business_license_path')) {
                $table->string('business_license_path')->nullable()->after('tax_number');
            }
            if (! Schema::hasColumn('companies', 'operating_license_path')) {
                $table->string('operating_license_path')->nullable()->after('business_license_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
        });

        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'operating_license_path')) {
                $table->dropColumn('operating_license_path');
            }
            if (Schema::hasColumn('companies', 'business_license_path')) {
                $table->dropColumn('business_license_path');
            }
            if (Schema::hasColumn('companies', 'tax_number')) {
                $table->dropColumn('tax_number');
            }
            if (Schema::hasColumn('companies', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('companies', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
