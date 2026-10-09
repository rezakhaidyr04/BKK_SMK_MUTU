<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * B2: pesan kontak/helpdesk publik. user_id nullable (boleh tamu).
     */
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('subject', 150);
            $table->text('message');
            $table->enum('status', ['baru', 'dibaca', 'dibalas'])->default('baru');
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
