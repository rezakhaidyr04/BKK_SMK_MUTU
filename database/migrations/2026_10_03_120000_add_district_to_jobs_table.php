<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lokasi nasional: tambah district (kecamatan, nullable, aditif).
 * Kolom 'location' dan 'city' existing TIDAK diubah/dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('district', 100)->nullable()->after('city');
            $table->index(['province', 'city', 'district'], 'jobs_province_city_district_index');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex('jobs_province_city_district_index');
            $table->dropColumn('district');
        });
    }
};
