<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->decimal('price_per_person', 10, 2)->nullable()->after('children');
            $table->decimal('estimated_total', 12, 2)->nullable()->after('price_per_person');
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn(['price_per_person', 'estimated_total']);
        });
    }
};
