<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->date('release_date')->nullable()->after('release_year');
        });

        DB::statement("ALTER TABLE movies DROP CONSTRAINT IF EXISTS movies_status_check");
        DB::statement("ALTER TABLE movies ADD CONSTRAINT movies_status_check CHECK (status IN ('draft', 'published', 'archived', 'coming_soon'))");
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('release_date');
        });

        DB::statement("ALTER TABLE movies DROP CONSTRAINT IF EXISTS movies_status_check");
        DB::statement("ALTER TABLE movies ADD CONSTRAINT movies_status_check CHECK (status IN ('draft', 'published', 'archived'))");
    }
};
