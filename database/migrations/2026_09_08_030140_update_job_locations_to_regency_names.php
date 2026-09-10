<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Map old location values to regency names
        $mapping = [
            'Cikampek Barat'     => 'Karawang',
            'Cikampek Timur'     => 'Karawang',
            'Cikampek'           => 'Karawang',
            'Cikampek & Sekitarnya' => 'Karawang',
        ];

        foreach ($mapping as $old => $new) {
            DB::table('jobs')
                ->where('location', $old)
                ->update(['location' => $new]);
        }
    }

    public function down(): void
    {
        // No reverse - we keep the new standardized names
    }
};
