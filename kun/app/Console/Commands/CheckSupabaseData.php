<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Payment;
use App\Models\MovieView;
use Illuminate\Support\Facades\DB;

class CheckSupabaseData extends Command
{
    protected $signature = 'supabase:check';
    protected $description = 'Check Supabase database connection and data';

    public function handle()
    {
        $this->info('🔍 Checking Supabase Connection...');
        $this->info('=====================================');
        $this->newLine();

        try {
            // Test connection
            $pdo = DB::connection()->getPdo();
            $this->info('✅ Database Connected Successfully!');
            $this->info('   Host: ' . config('database.connections.pgsql.host'));
            $this->info('   Database: ' . config('database.connections.pgsql.database'));
            $this->newLine();

            // Count data
            $this->info('📊 Current Data in Supabase:');
            $this->info('----------------------------');
            
            $data = [
                'Users' => User::count(),
                'Roles' => Role::count(),
                'Permissions' => Permission::count(),
                'Movies' => Movie::count(),
                'Genres' => Genre::count(),
                'Payments' => Payment::count(),
                'Movie Views' => MovieView::count(),
            ];

            foreach ($data as $label => $count) {
                $this->line("  {$label}: " . ($count > 0 ? "<fg=green>{$count}</>" : "<fg=yellow>{$count}</>"));
            }
            
            $this->newLine();

            // Show sample users
            $this->info('👥 Sample Users:');
            $users = User::with('roles')->take(5)->get();
            
            if ($users->count() > 0) {
                foreach ($users as $user) {
                    $roles = $user->roles->pluck('name')->implode(', ') ?: 'No roles';
                    $this->line("  - {$user->name} ({$user->email}) - Roles: {$roles}");
                }
            } else {
                $this->warn('  No users found in database');
            }

            $this->newLine();

            // Show sample movies
            $this->info('🎬 Sample Movies:');
            $movies = Movie::take(5)->get();
            
            if ($movies->count() > 0) {
                foreach ($movies as $movie) {
                    $this->line("  - {$movie->title} ({$movie->release_year})");
                }
            } else {
                $this->warn('  No movies found in database');
            }

            $this->newLine();
            $this->info('✅ All data is coming from Supabase!');

        } catch (\Exception $e) {
            $this->error('❌ Database Connection Failed!');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
