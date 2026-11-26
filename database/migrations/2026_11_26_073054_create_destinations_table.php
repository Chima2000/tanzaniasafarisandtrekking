<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('short')->nullable();
            $table->string('title');
            $table->string('full_name')->nullable();
            $table->string('region')->nullable();
            $table->json('needles')->nullable();
            $table->string('starts')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->string('headline')->nullable();
            $table->text('description')->nullable();
            $table->text('story_intro')->nullable();
            $table->json('story_chapters')->nullable();
            $table->json('highlights')->nullable();
            $table->json('wildlife')->nullable();
            $table->text('best_time')->nullable();
            $table->text('getting_there')->nullable();
            $table->json('tips')->nullable();
            $table->json('facts')->nullable();
            $table->json('faqs')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
