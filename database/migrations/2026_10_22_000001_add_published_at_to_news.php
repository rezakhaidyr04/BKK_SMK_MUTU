<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Defensif: create_news_table sudah membuat kolom + index ini.
        // Tanpa cek, migrate:fresh gagal "Duplicate column name 'published_at'".
        if (Schema::hasColumn('news', 'published_at')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('news', 'published_at')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'published_at']);
            $table->dropColumn('published_at');
        });
    }
};
