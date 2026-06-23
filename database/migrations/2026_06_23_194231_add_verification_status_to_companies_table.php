<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("companies", function (Blueprint $table) {
            // pending = baru daftar, belum ditinjau
            // verified = disetujui admin
            // rejected = ditolak admin
            $table
                ->string("verification_status")
                ->default("pending")
                ->after("is_verified");
            $table
                ->text("rejection_reason")
                ->nullable()
                ->after("verification_status");
        });

        // Sinkronkan data lama: company yang is_verified=true → status 'verified'
        DB::table("companies")
            ->where("is_verified", true)
            ->update(["verification_status" => "verified"]);
    }

    public function down(): void
    {
        Schema::table("companies", function (Blueprint $table) {
            $table->dropColumn(["verification_status", "rejection_reason"]);
        });
    }
};
