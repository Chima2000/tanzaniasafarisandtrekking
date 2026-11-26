<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->string('disk', 30);
            $table->string('path', 500);
            $table->string('filename');
            $table->string('extension', 20)->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->enum('category', ['image', 'audio', 'video', 'document', 'archive', 'other'])->default('other');
            $table->string('folder', 255)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('title')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('file_hash', 64)->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('file_modified_at')->nullable();
            $table->timestamps();

            $table->unique(['disk', 'path']);
            $table->index(['category', 'created_at']);
            $table->index('folder');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_library');
    }
};
