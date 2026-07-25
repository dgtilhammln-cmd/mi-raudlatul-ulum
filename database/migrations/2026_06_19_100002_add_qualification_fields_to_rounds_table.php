<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->enum('round_type', [
                'qualification',  // Babak Kualifikasi
                'group_stage',    // Babak Penyisihan
                'round_of_64',    // 64 Besar
                'round_of_32',    // 32 Besar
                'quarter_final',  // Perempat Final / 8 Besar
                'semi_final',     // Semifinal / 4 Besar
                'final',          // Grand Final
            ])->nullable()->after('sequence');

            // Top-N peserta yang lolos ke babak berikutnya
            $table->integer('advancement_limit')->nullable()->after('passing_score');

            // Apakah proses advancement otomatis setelah semua nilai keluar
            $table->boolean('auto_advance')->default(true)->after('advancement_limit');

            // Status proses advancement: pending, processing, done
            $table->enum('advancement_status', ['pending', 'processing', 'done'])->default('pending')->after('auto_advance');
        });
    }

    public function down(): void
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropColumn(['round_type', 'advancement_limit', 'auto_advance', 'advancement_status']);
        });
    }
};
