<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->nullable();
            $table->string('email');
            $table->json('notification_types');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('email');
        });

        $fallback = config('mail.book_now_recipient');

        if (filled($fallback)) {
            DB::table('notification_recipients')->insert([
                'name' => 'Bookings desk',
                'email' => strtolower(trim((string) $fallback)),
                'notification_types' => json_encode(['booking', 'contact']),
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_recipients');
    }
};
