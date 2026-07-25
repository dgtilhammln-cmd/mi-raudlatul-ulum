<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Sistem penilaian: 'qualification' (eliminasi babak) atau 'point' (akumulasi poin)
            $table->enum('scoring_system', ['qualification', 'point'])->default('qualification')->after('status');
            // Apakah leaderboard ditampilkan ke publik / dashboard peserta
            $table->boolean('leaderboard_visible')->default(true)->after('scoring_system');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['scoring_system', 'leaderboard_visible']);
        });
    }
};
