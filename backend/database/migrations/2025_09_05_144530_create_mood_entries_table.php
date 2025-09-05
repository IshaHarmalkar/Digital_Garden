<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mood_id')->constrained()->cascadeOnDelete();
            $table->string('slot', 20); // morning, afternoon, night (validated in app)
            $table->date('entry_date');
            $table->timestamps();

            $table->unique(['slot', 'entry_date']); // only 1 per slot per day
            $table->index('entry_date'); // faster summary queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_entries');
    }
};
