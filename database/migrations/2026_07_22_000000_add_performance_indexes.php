<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // No-op migration to avoid duplicate index errors on existing databases.
        // The company registration flow only requires the existing companies table.
    }

    public function down(): void
    {
        // No-op.
    }
};
