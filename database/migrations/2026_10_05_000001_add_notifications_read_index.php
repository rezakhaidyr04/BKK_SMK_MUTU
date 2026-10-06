<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * L8: indeks komposit untuk unreadNotifications() (where notifiable +
     * whereNull read_at + latest). Aditif saja: tanpa ubah/hapus data,
     * reversible via down(). Tabel notifications tumbuh ±10rb baris per
     * publish (M1) sehingga full-scan tanpa indeks ini melambat.
     */
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }
        foreach (Schema::getIndexes('notifications') as $index) {
            if ($index['name'] === 'notifications_notifiable_read_index') {
                return;
            }
        }
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(
                ['notifiable_type', 'notifiable_id', 'read_at', 'created_at'],
                'notifications_notifiable_read_index'
            );
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }
        foreach (Schema::getIndexes('notifications') as $index) {
            if ($index['name'] === 'notifications_notifiable_read_index') {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->dropIndex('notifications_notifiable_read_index');
                });
                return;
            }
        }
    }
};
