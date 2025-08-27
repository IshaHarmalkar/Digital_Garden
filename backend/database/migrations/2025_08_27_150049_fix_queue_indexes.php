<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notion_queues', function (Blueprint $table) {
            // Drop the incorrectly named single-column index
            $table->dropIndex('id');

            // Add proper composite index
            $table->index(['queue_type', 'id'], 'notion_queue_type_id_index');
        });

        Schema::table('native_queues', function (Blueprint $table) {
            // Drop the old composite index by its actual name
            $table->dropIndex('native_queues_queue_type_id_index');

            // Add the new one with a custom name
            $table->index(['queue_type', 'id'], 'native_queue_type_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('notion_queues', function (Blueprint $table) {
            $table->dropIndex('notion_queue_type_id_index');
            $table->index('queue_type', 'id'); // restore the same "mistake"
        });

        Schema::table('native_queues', function (Blueprint $table) {
            $table->dropIndex('native_queue_type_id_index');
            $table->index(['queue_type', 'id']); // back to default name
        });
    }
};
