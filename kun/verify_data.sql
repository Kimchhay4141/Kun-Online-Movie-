-- Connect to Kun_Onlien_Movie database first!
-- Then run this SQL file

-- Check all table counts
SELECT 'users' as table_name, COUNT(*) as records FROM users
UNION ALL
SELECT 'roles', COUNT(*) FROM roles
UNION ALL
SELECT 'permissions', COUNT(*) FROM permissions
UNION ALL
SELECT 'movies', COUNT(*) FROM movies
UNION ALL
SELECT 'genres', COUNT(*) FROM genres
UNION ALL
SELECT 'movie_videos', COUNT(*) FROM movie_videos
UNION ALL
SELECT 'movie_views', COUNT(*) FROM movie_views
UNION ALL
SELECT 'favorites', COUNT(*) FROM favorites
UNION ALL
SELECT 'watchlists', COUNT(*) FROM watchlists
UNION ALL
SELECT 'payments', COUNT(*) FROM payments;

-- Show sample users
SELECT id, name, email, subscription_plan, subscription_status 
FROM users 
LIMIT 10;

-- Show sample movies
SELECT id, title, rating, view_count, status, is_featured 
FROM movies 
LIMIT 10;

-- Show sample genres
SELECT id, name, icon, sort_order 
FROM genres 
ORDER BY sort_order;
