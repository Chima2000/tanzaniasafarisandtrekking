<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('cost_per_person', 10, 2)->nullable()->after('excludes');
            $table->decimal('profit_per_person', 10, 2)->nullable()->after('cost_per_person');
            $table->json('blocked_dates')->nullable()->after('profit_per_person');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['cost_per_person', 'profit_per_person', 'blocked_dates']);
        });
    }
};
