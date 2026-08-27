<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('users', ['role'], 'users_role_index');
        $this->addIndexIfMissing('jobs', ['company_name'], 'jobs_company_name_index');
        $this->addIndexIfMissing('messages', ['conversation_id', 'is_read'], 'messages_conversation_read_index');
        $this->addIndexIfMissing('activity_logs', ['actor_type', 'actor_id'], 'activity_logs_actor_index');
    }

    public function down(): void
    {
        $this->dropIndexIfExists('users', 'users_role_index');
        $this->dropIndexIfExists('jobs', 'jobs_company_name_index');
        $this->dropIndexIfExists('messages', 'messages_conversation_read_index');
        $this->dropIndexIfExists('activity_logs', 'activity_logs_actor_index');
    }

    private function addIndexIfMissing(string $table, array $columns, string $name): void
    {
        foreach (Schema::getIndexes($table) as $index) {
            if ($index['name'] === $name || $index['columns'] === $columns) {
                return;
            }
        }

        Schema::table($table, function ($blueprint) use ($columns, $name) {
            $blueprint->index($columns, $name);
        });
    }

    private function dropIndexIfExists(string $table, string $name): void
    {
        foreach (Schema::getIndexes($table) as $index) {
            if ($index['name'] === $name) {
                Schema::table($table, function ($blueprint) use ($name) {
                    $blueprint->dropIndex($name);
                });

                return;
            }
        }
    }
};
