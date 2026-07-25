<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('round_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'submitted', 'auto_submitted'])->default('pending');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('violation_count')->default(0);
            $table->decimal('score_pg', 10, 2)->default(0);
            $table->decimal('score_essay', 10, 2)->default(0);
            $table->decimal('total_score', 10, 2)->default(0);
            $table->integer('correct_count')->default(0);
            $table->integer('wrong_count')->default(0);
            $table->integer('unanswered_count')->default(0);
            $table->enum('result_status', ['pending', 'pg_scored', 'essay_pending', 'final'])->default('pending');
            $table->dateTime('result_published_at')->nullable();
            $table->integer('rank')->nullable();
            $table->timestamps();

            $table->unique(['participant_id', 'round_id']);
            $table->index('result_status');
        });

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exam_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->integer('display_order');
            $table->json('shuffled_options')->nullable();

            $table->unique(['session_id', 'question_id']);
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exam_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('options')->nullOnDelete();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score', 8, 2)->default(0);
            $table->longText('essay_answer')->nullable();
            $table->text('essay_feedback')->nullable();
            $table->enum('essay_status', ['pending', 'graded'])->default('pending');
            $table->dateTime('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'question_id']);
            $table->index('essay_status');
        });

        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exam_sessions')->cascadeOnDelete();
            $table->enum('type', [
                'tab_switch', 'window_blur', 'fullscreen_exit',
                'browser_minimize', 'copy_attempt', 'paste_attempt',
                'right_click', 'keyboard_shortcut'
            ]);
            $table->dateTime('occurred_at');
            $table->json('metadata')->nullable();

            $table->index(['session_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violations');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_sessions');
    }
};
