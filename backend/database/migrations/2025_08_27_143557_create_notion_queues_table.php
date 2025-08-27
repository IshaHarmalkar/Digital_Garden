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
        Schema::create('notion_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notion_content_id')->constrained('notion_contents')->cascadeOnDelete();
            $table->enum('queue_type', ['main', 'priority'])->default('main');
            $table->timestamps();

            // index
            $table->index('queue_type', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notion_queues');
    }
};
