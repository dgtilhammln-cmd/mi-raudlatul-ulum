<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->enum('column_position', ['left', 'right'])->default('left')->comment('Left column goes down, right column goes up');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_images');
    }
};
