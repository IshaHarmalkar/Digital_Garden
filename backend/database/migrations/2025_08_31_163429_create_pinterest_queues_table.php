<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinterest_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinterest_content_id')
                ->constrained('pinterest_contents')
                ->cascadeOnDelete();
            $table->enum('queue_type', ['main', 'priority'])->default('main');
            $table->timestamps();

            // composite index
            $table->index(['queue_type', 'id'], 'pinterest_queue_type_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinterest_queues', function (Blueprint $table) {
            // Drop composite index first
            $table->dropIndex('pinterest_queue_type_id_index');
        });

        Schema::dropIfExists('pinterest_queues');
    }
};
