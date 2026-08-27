<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function ($table) {
            $table->string('logo')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('companies', 'logo')) {
            Schema::table('companies', function ($table) {
                $table->dropColumn('logo');
            });
        }
    }
};
