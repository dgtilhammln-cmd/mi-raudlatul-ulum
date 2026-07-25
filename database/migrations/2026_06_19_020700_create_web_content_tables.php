<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('partner_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path');
            $table->string('url')->nullable();
            $table->enum('type', ['partner', 'sponsor'])->default('partner');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('instagram_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('link_url');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_feeds');
        Schema::dropIfExists('partner_logos');
        Schema::dropIfExists('web_settings');
    }
};
