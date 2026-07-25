<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('subject', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained('question_banks')->cascadeOnDelete();
            $table->enum('type', ['multiple_choice', 'essay'])->default('multiple_choice');
            $table->longText('content');
            $table->string('content_image_url', 500)->nullable();
            $table->longText('explanation')->nullable();
            $table->decimal('score', 8, 2)->default(1);
            $table->decimal('negative_score', 8, 2)->default(0);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->string('category', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bank_id', 'type']);
            $table->index(['bank_id', 'difficulty']);
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('content_image_url', 500)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('order_index')->default(0);

            $table->index('question_id');
        });

        Schema::create('round_banks', function (Blueprint $table) {
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_id')->constrained('question_banks')->cascadeOnDelete();
            $table->integer('question_count')->default(10);

            $table->primary(['round_id', 'bank_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('round_banks');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('question_banks');
    }
};
