<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->string('mou_path')->nullable()->after('operating_license_path');
            $table->string('mou_number')->nullable()->after('mou_path');
            $table->date('mou_signed_at')->nullable()->after('mou_number');
            $table->date('mou_expires_at')->nullable()->after('mou_signed_at');
            $table->foreignId('reviewed_by')->nullable()->after('rejection_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false)->after('is_active');
            $table->timestamp('password_changed_at')->nullable()->after('must_change_password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['must_change_password', 'password_changed_at']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['mou_path', 'mou_number', 'mou_signed_at', 'mou_expires_at', 'reviewed_by', 'reviewed_at']);
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};