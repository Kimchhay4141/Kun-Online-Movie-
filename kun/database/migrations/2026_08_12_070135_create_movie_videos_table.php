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
        Schema::create('movie_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('video_url');
            $table->enum('video_type', ['movie', 'trailer', 'teaser', 'behind_scenes', 'clip'])->default('movie');
            $table->string('quality')->nullable(); // 480p, 720p, 1080p, 4K
            $table->integer('duration')->nullable(); // in seconds
            $table->integer('file_size')->nullable(); // in MB
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('movie_id');
            $table->index('video_type');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_videos');
    }
};
