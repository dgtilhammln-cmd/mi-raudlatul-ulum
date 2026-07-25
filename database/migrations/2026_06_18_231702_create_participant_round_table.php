<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participant_round', function (Blueprint $table) {
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['participant_id', 'round_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_round');
    }
};
