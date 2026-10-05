<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Relasi kanonis (nullable, backward-compatible; company_name tetap ada).
            $table->foreignId('company_id')->nullable()->after('user_id')
                ->constrained('companies')->nullOnDelete();
            $table->index('company_name');
            // Anti-spam: satu pengguna satu ulasan per perusahaan.
            $table->unique(['user_id', 'company_name'], 'reviews_user_company_unique');
        });

        // Backfill non-destruktif dari nama yang cocok persis.
        DB::table('reviews as r')
            ->join('companies as c', 'c.name', '=', 'r.company_name')
            ->whereNull('r.company_id')
            ->update(['r.company_id' => DB::raw('c.id')]);
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_user_company_unique');
            $table->dropIndex(['company_name']);
            $table->dropConstrainedForeignId('company_id');
        });
    }
};
