<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();
        });

        // Generate slugs for existing events
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            DB::table('events')
                ->where('id', $event->id)
                ->update(['slug' => Str::slug($event->name . '-' . Str::random(4))]);
        }

        // Make it unique
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
