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
        Schema::create('native_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('native_id')->constrained('natives')->cascadeOnDelete();
            $table->enum('queue_type', ['main', 'priority'])->default('main');
            $table->timestamps();

            // composite Key for indexing
            $table->index(['queue_type', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('native_queues');
    }
};
