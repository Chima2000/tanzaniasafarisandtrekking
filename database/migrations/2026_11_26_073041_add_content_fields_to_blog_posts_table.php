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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('quote')->nullable()->after('body');
            $table->json('sections')->nullable()->after('quote');
            $table->json('tips')->nullable()->after('sections');
            $table->json('tags')->nullable()->after('tips');
            $table->string('cta_label')->nullable()->after('tags');
            $table->string('cta_url_name')->nullable()->after('cta_label');
            $table->string('cta_url_param')->nullable()->after('cta_url_name');
            $table->json('cta_query')->nullable()->after('cta_url_param');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'quote',
                'sections',
                'tips',
                'tags',
                'cta_label',
                'cta_url_name',
                'cta_url_param',
                'cta_query',
            ]);
        });
    }
};
