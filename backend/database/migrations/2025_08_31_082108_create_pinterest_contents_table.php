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
        Schema::create('pinterest_contents', function (Blueprint $table) {
            $table->id();
            $table->string('pin_id');
            $table->string('board_id');
            $table->timestamps();

            // indexing
            $table->index('board_id');

            $table->unique(['pin_id', 'board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinterest_contents');
    }
};
