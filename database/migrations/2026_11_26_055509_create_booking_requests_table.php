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
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('package_slug')->nullable();
            $table->string('package_name')->nullable();
            $table->string('interest')->nullable();
            $table->string('budget')->nullable();
            $table->unsignedTinyInteger('duration_days')->nullable();
            $table->json('destinations')->nullable();
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->string('accommodation')->nullable();
            $table->date('travel_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('payload')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
