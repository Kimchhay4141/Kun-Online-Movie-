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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->year('release_year')->nullable();
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->string('language')->default('en');
            $table->string('country')->nullable();
            $table->string('director')->nullable();
            $table->text('cast')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('banner')->nullable();
            $table->string('trailer_url')->nullable();
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('view_count')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('content_rating', ['G', 'PG', 'PG-13', 'R', 'NC-17'])->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('is_featured');
            $table->index('view_count');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
