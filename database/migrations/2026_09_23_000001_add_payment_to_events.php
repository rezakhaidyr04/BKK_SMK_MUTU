<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('location');
            $table->decimal('price', 12, 2)->nullable()->after('is_paid');
            $table->integer('quota')->nullable()->after('price');
            $table->text('payment_instructions')->nullable()->after('quota');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('status'); // unpaid, pending, verified, rejected
            $table->string('payment_proof')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_proof');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'price', 'quota', 'payment_instructions']);
        });
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_proof', 'paid_at']);
        });
    }
};
