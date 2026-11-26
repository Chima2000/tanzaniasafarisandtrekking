<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 32)->nullable();
            $table->boolean('enabled')->default(true);
            $table->string('message', 300)->nullable();
            $table->string('popup_title', 120)->nullable();
            $table->string('popup_description', 300)->nullable();
            $table->string('popup_note', 180)->nullable();
            $table->string('corner', 20)->default('bottom-right');
            $table->unsignedSmallInteger('offset_x')->default(24);
            $table->unsignedSmallInteger('offset_y')->default(24);
            $table->unsignedSmallInteger('icon_width')->default(56);
            $table->unsignedSmallInteger('icon_height')->default(56);
            $table->string('icon_color', 7)->default('#25D366');
            $table->boolean('show_on_all_pages')->default(true);
            $table->json('pages')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_setting_id')->constrained()->cascadeOnDelete();
            $table->string('section', 40)->nullable();
            $table->string('name', 80);
            $table->string('title', 80);
            $table->string('phone_number', 32);
            $table->string('photo_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_contacts');
        Schema::dropIfExists('whatsapp_settings');
    }
};
