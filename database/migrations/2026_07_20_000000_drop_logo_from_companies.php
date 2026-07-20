<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('companies', 'logo')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('logo');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('companies', 'logo')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('logo')->nullable();
            });
        }
    }
};
