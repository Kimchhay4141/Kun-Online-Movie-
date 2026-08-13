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
        Schema::create('movie_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->integer('watch_duration')->default(0); // seconds watched
            $table->integer('total_duration')->nullable(); // total movie duration
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->timestamp('last_watched_at')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'movie_id']);
            $table->index('last_watched_at');
            $table->unique(['user_id', 'movie_id']); // One record per user per movie
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_views');
    }
};
