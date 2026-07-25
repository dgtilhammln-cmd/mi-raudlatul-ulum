<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('access_code')->nullable()->after('participant_code');
        });

        \Illuminate\Support\Facades\DB::table('participants')->update(['access_code' => '123456']);
        \Illuminate\Support\Facades\DB::table('users')->where('role', 'participant')->update(['password' => bcrypt('123456')]);
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('access_code');
        });
    }
};
