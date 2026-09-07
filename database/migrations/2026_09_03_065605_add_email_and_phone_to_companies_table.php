<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (! Schema::hasColumn('companies', 'email')) {
                $table->string('email')->nullable()->after('website');
            }
            if (! Schema::hasColumn('companies', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('companies', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
