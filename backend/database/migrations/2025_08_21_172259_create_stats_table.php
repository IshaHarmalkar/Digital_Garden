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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->morphs('statable'); // statable_id + statble_type -> makes mutliple foreign key origns to work.
            $table->unsignedInteger('like_count')->default(0);
            $table->timestamp('last_sent_at')->nullable();
            $table->boolean('see_again_soon')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
