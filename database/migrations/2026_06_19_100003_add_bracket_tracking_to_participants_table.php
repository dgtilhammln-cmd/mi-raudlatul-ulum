<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Babak terakhir yang diikuti peserta (sequence-nya)
            $table->integer('current_round_sequence')->default(0)->after('status');

            // Di babak mana peserta gugur (null = masih aktif / belum mulai)
            $table->integer('eliminated_at_round')->nullable()->after('current_round_sequence');

            // Juara?
            $table->boolean('is_champion')->default(false)->after('eliminated_at_round');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['current_round_sequence', 'eliminated_at_round', 'is_champion']);
        });
    }
};
