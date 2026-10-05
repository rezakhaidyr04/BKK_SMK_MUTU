<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lokasi nasional: tambah province + city (nullable, aditif).
 * Kolom 'location' existing TIDAK diubah/dihapus (display + fallback).
 * Backfill hanya mengisi kolom NULL dari location yang cocok UNIK
 * dengan master (non-destruktif; ambigu/tidak cocok dibiarkan NULL).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('province', 100)->nullable()->after('location');
            $table->string('city', 100)->nullable()->after('province');
            $table->index(['province', 'city'], 'jobs_province_city_index');
        });

        $map = config('regions.cities', []);

        if (! empty($map)) {
            $short = [];
            foreach ($map as $province => $cities) {
                foreach ($cities as $city) {
                    $key = mb_strtolower(trim((string) preg_replace('/^(kabupaten|kota)\s+/i', '', $city)));
                    $short[$key][] = ['province' => $province, 'city' => $city];
                }
            }

            DB::table('jobs')->whereNull('city')->whereNotNull('location')
                ->orderBy('id')->chunkById(500, function ($jobs) use ($short) {
                    foreach ($jobs as $job) {
                        $loc = mb_strtolower(trim((string) $job->location));

                        if ($loc === '' || ! isset($short[$loc]) || count($short[$loc]) !== 1) {
                            continue;
                        }

                        DB::table('jobs')->where('id', $job->id)->update([
                            'province' => $short[$loc][0]['province'],
                            'city' => $short[$loc][0]['city'],
                        ]);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex('jobs_province_city_index');
            $table->dropColumn(['province', 'city']);
        });
    }
};
