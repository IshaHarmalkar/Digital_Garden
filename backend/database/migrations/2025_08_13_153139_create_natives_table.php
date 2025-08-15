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
        Schema::create('natives', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['text', 'image']);
            $table->text('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('like_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('natives');
    }
};
