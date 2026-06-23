<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("jobs", function (Blueprint $table) {
            // Kolom-kolom ini seharusnya nullable karena tidak wajib diisi saat buat lowongan
            $table->string("position")->nullable()->change();
            $table->string("location")->nullable()->change();
            $table->string("job_type")->nullable()->change();
            $table->date("deadline")->nullable()->change();
            $table->string("status")->default("draft")->change();
        });
    }

    public function down(): void
    {
        Schema::table("jobs", function (Blueprint $table) {
            $table->string("position")->nullable(false)->change();
            $table->string("location")->nullable(false)->change();
            $table->string("job_type")->nullable(false)->change();
            $table->date("deadline")->nullable(false)->change();
        });
    }
};
