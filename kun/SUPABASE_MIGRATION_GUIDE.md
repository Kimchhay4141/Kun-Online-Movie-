# Supabase Migration Guide

## Prerequisites
✅ PHP PostgreSQL extension installed (`php_pgsql.dll`)
✅ Composer dependencies installed
✅ Supabase account created with a project

---

## Step 1: Get Supabase Credentials

1. Go to https://app.supabase.com
2. Select your project
3. Navigate to **Settings** → **Database**
4. Find your connection details:

### Connection String Format:
```
postgresql://postgres:[YOUR-PASSWORD]@db.your-project-ref.supabase.co:5432/postgres
```

### Extract these values:
- **DB_HOST**: `db.your-project-ref.supabase.co`
- **DB_PORT**: `5432`
- **DB_DATABASE**: `postgres`
- **DB_USERNAME**: `postgres`
- **DB_PASSWORD**: `[YOUR-PASSWORD]`

### Optional API Keys (for Supabase features):
Navigate to **Settings** → **API**:
- **SUPABASE_URL**: Your Project URL
- **SUPABASE_ANON_KEY**: anon/public key
- **SUPABASE_SERVICE_KEY**: service_role key (keep secret!)

---

## Step 2: Update .env File

Your `.env` file has been configured with:

```env
# Supabase Database Configuration
DB_CONNECTION=pgsql
DB_HOST=your-project-ref.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-database-password

# Supabase API Configuration (optional - for direct API usage)
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=your-supabase-anon-key
SUPABASE_SERVICE_KEY=your-supabase-service-role-key
```

**Replace all placeholders with your actual Supabase credentials!**

---

## Step 3: Test Database Connection

```bash
# Clear config cache
php artisan config:clear

# Test connection
php artisan db:show
```

If successful, you'll see your Supabase database information!

---

## Step 4: Run Migrations

### Option A: Fresh Migration (Clean Start)
```bash
# Drop all tables and re-migrate (WARNING: Deletes all data!)
php artisan migrate:fresh

# Run seeders
php artisan db:seed
```

### Option B: Regular Migration (Preserves existing data)
```bash
# Run pending migrations only
php artisan migrate
```

### Option C: Check migration status
```bash
# See which migrations have run
php artisan migrate:status
```

---

## Step 5: Verify Tables in Supabase

1. Go to Supabase Dashboard
2. Navigate to **Table Editor**
3. You should see all your tables:
   - users
   - roles
   - permissions
   - movies
   - genres
   - movie_videos
   - movie_views
   - favorites
   - watchlists
   - payments
   - And all pivot tables

---

## Migrating Existing Data

### If you have data in local PostgreSQL:

#### 1. Export from Local Database
```bash
# Full backup (structure + data)
pg_dump -h 127.0.0.1 -U postgres -d Kun_Onlien_Movie -f kun_backup.sql

# Schema only
pg_dump -h 127.0.0.1 -U postgres -d Kun_Onlien_Movie --schema-only -f kun_schema.sql

# Data only
pg_dump -h 127.0.0.1 -U postgres -d Kun_Onlien_Movie --data-only -f kun_data.sql
```

#### 2. Import to Supabase

**Method A: Command Line**
```bash
psql "postgresql://postgres:[YOUR-PASSWORD]@db.your-project-ref.supabase.co:5432/postgres" -f kun_backup.sql
```

**Method B: Supabase Dashboard**
1. Navigate to **SQL Editor**
2. Click **New Query**
3. Paste SQL dump content
4. Click **Run**

---

## Common Issues & Solutions

### Issue 1: SSL Connection Error
**Solution**: Ensure SSL mode is set to 'require' in `config/database.php` (already configured)

### Issue 2: Connection Timeout
**Solution**: Check your internet connection and Supabase credentials

### Issue 3: "relation already exists" error
**Solution**: Run `php artisan migrate:fresh` to start clean (WARNING: deletes data)

### Issue 4: "permission denied" error
**Solution**: Make sure you're using the correct database password and connection string

---

## Enable Supabase Features (Optional)

### Row Level Security (RLS)
After migration, you can enable RLS in Supabase Dashboard:
1. Go to **Authentication** → **Policies**
2. Enable RLS for specific tables
3. Create policies for access control

### Real-time Subscriptions
Enable real-time for tables:
1. Go to **Database** → **Replication**
2. Enable for specific tables
3. Use Supabase client libraries to subscribe

### Storage
Configure storage buckets in **Storage** section for movie posters, videos, etc.

---

## Production Deployment Checklist

- [ ] Update `.env` with production Supabase credentials
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up database backups in Supabase dashboard
- [ ] Enable RLS policies for security
- [ ] Test all CRUD operations
- [ ] Monitor database performance

---

## Quick Commands Reference

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Fresh start with seeders
php artisan migrate:fresh --seed

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check migration status
php artisan migrate:status
```

---

## Need Help?

- Supabase Documentation: https://supabase.com/docs
- Laravel Database Docs: https://laravel.com/docs/database
- Supabase Discord: https://discord.supabase.com

---

**Last Updated**: Ready to migrate! 🚀
