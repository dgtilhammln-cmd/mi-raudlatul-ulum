<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('banner_url', 500)->nullable();
            $table->string('category', 100)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organizer_id', 'status']);
        });

        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('sequence')->default(1);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration_minutes')->default(60);
            $table->integer('max_questions')->default(30);
            $table->decimal('passing_score', 5, 2)->nullable();
            $table->boolean('randomize_questions')->default(true);
            $table->boolean('randomize_options')->default(true);
            $table->boolean('allow_review')->default(true);
            $table->integer('warning_threshold')->default(3);
            $table->integer('auto_submit_threshold')->default(5);
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();

            $table->index(['event_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rounds');
        Schema::dropIfExists('events');
    }
};
