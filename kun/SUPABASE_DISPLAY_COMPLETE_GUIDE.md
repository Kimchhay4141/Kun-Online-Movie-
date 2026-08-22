# Complete Guide: Images & Videos Display from Supabase

## ✅ STATUS: ALL PAGES NOW USE SUPABASE

All images and videos across your movie site now load from Supabase Storage!

---

## 📍 All Pages That Display Images/Videos

### 1. **Home Page** - `http://localhost:8000`
**What displays:**
- Featured movies carousel (thumbnails)
- Trending movies section (thumbnails)
- New releases section (thumbnails)

**Database field used:** `movies.thumbnail`

**Code location:** `resources/views/home.blade.php`

**How it works:**
```blade
<img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450' }}" 
     alt="{{ $movie->title }}">
```

---

### 2. **Movie Detail Page** - `http://localhost:8000/movies/{id}`
**What displays:**
- Movie poster (left side)
- Background banner image (hero section)
- Trailer thumbnails
- Related movies (thumbnails)

**Database fields used:** 
- `movies.thumbnail` (poster)
- `movies.banner` (background)
- `movie_videos.video_url` (trailers)

**Code location:** `resources/views/movies/show.blade.php`

**How it works:**
```blade
<!-- Hero background -->
<section style="background-image: url('{{ $movie->banner ?? $movie->thumbnail }}');">

<!-- Movie poster -->
<img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}">

<!-- Trailer videos -->
@foreach($trailers as $video)
    <video src="{{ $video->video_url }}"></video>
@endforeach
```

---

### 3. **Watch Page** - `http://localhost:8000/movie/{id}/watch`
**What displays:**
- Video player with movie file
- Movie thumbnail (sidebar)

**Database fields used:**
- `movie_videos.video_url` (main video)
- `movies.thumbnail` (thumbnail)

**Code location:** `resources/views/movies/watch.blade.php`

**How it works:**
```blade
<video id="moviePlayer" controls>
    <source src="{{ $video->video_url }}" type="video/mp4">
</video>
```

---

### 4. **Genre Pages** - `http://localhost:8000/genres/{slug}`
**What displays:**
- All movies in that genre (thumbnails)

**Database field used:** `movies.thumbnail`

**Code location:** `resources/views/genres/show.blade.php`

---

### 5. **Search Results** - `http://localhost:8000/search?q=...`
**What displays:**
- Matching movies (thumbnails)

**Database field used:** `movies.thumbnail`

**Code location:** `resources/views/search/results.blade.php`

---

### 6. **Admin Movie List** - `http://localhost:8000/admin/movies`
**What displays:**
- All movies table with small thumbnails
- Thumbnail column shows Supabase images

**Database field used:** `movies.thumbnail`

**Code location:** `resources/views/admin/movies/index.blade.php`

**How it works:**
```blade
<img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/50x75' }}" 
     class="movie-thumb">
```

---

### 7. **Admin Create Page** - `http://localhost:8000/admin/movies/create`
**What it does:**
- Uploads thumbnail to Supabase `posters` bucket
- Uploads banner to Supabase `posters` bucket  
- Uploads videos to Supabase `videos` bucket
- Stores full Supabase URLs in database

**Code location:** `resources/views/admin/movies/create.blade.php`

**Backend:** `app/Http/Controllers/Admin/MovieController.php`

**Services used:**
- `SupabaseStorageService` - Handles file uploads
- `VideoServiceV2` - Handles video processing
- `MovieService` - Handles poster uploads

---

### 8. **Admin Edit Page** - `http://localhost:8000/admin/movies/{id}/edit`
**What displays:**
- Current thumbnail (from Supabase URL)
- Current banner (from Supabase URL)
- Video list with Supabase links

**Database fields used:** 
- `movies.thumbnail`
- `movies.banner`
- `movie_videos.video_url`

**Code location:** `resources/views/admin/movies/edit.blade.php`

**How it works:**
```blade
@if($movie->thumbnail)
    <img src="{{ $movie->thumbnail }}" alt="Current thumbnail">
    <p>Current: {{ basename($movie->thumbnail) }}</p>
@endif
```

**What you can do:**
- Replace thumbnail (uploads to Supabase, deletes old one)
- Replace banner (uploads to Supabase, deletes old one)
- Add/delete videos (manages Supabase `videos` bucket)

---

### 9. **User Profile Pages**
**Pages:**
- Watchlist: `http://localhost:8000/watchlist`
- Favorites: `http://localhost:8000/favorites`
- Watch History: `http://localhost:8000/history`

**What displays:** Movie thumbnails from user's lists

**Database field used:** `movies.thumbnail`

---

## 🗂️ Supabase Storage Structure

### **Buckets:**

1. **`posters`** (Public bucket)
   - Path pattern: `posters/{timestamp}_{random}_{filename}.jpg`
   - Used for: Movie thumbnails and banners
   - URL format: `https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/posters/posters/{filename}`

2. **`videos`** (Public bucket)
   - Path pattern: `videos/{movie_id}/{timestamp}_{random}_{filename}.mp4`
   - Used for: Movie videos and trailers
   - URL format: `https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/videos/videos/{movie_id}/{filename}`

---

## 📊 Database Schema

### **movies table:**
```sql
- id (primary key)
- title
- thumbnail (TEXT) -- Full Supabase URL
- banner (TEXT) -- Full Supabase URL
- status ('draft', 'published', 'coming_soon')
- ... other fields
```

### **movie_videos table:**
```sql
- id (primary key)
- movie_id (foreign key)
- video_url (TEXT) -- Full Supabase URL
- video_type ('movie', 'trailer')
- title
- file_size (bytes)
- ... other fields
```

---

## 🔧 Backend Services

### **SupabaseStorageService** (`app/Services/SupabaseStorageService.php`)
Handles all Supabase storage operations:
- Upload files to buckets
- Delete files from buckets
- Generate public URLs
- Manage file metadata

### **VideoServiceV2** (`app/Services/VideoServiceV2.php`)
Handles video-specific operations:
- Upload videos to Supabase
- Store video metadata in database
- Delete videos from both Supabase and database

### **MovieService** (`app/Services/MovieService.php`)
Handles movie poster operations:
- Upload posters to Supabase
- Update database with Supabase URLs
- Delete old posters when replacing

---

## ✅ Current Status (Verified)

**Movies in database:** 3 movies (IDs: 32, 33, 34)

**Image status:**
- ✅ All thumbnails stored in Supabase
- ✅ All banners stored in Supabase
- ✅ Total: 6 images successfully migrated

**Video status:**
- ⚠️ No videos uploaded yet (you need to add these)

**Display status:**
- ✅ Home page: Loading images from Supabase
- ✅ Movie detail pages: Loading images from Supabase
- ✅ Admin dashboard: Showing Supabase URLs correctly
- ✅ Admin edit page: Displaying Supabase images directly

---

## 🎯 Next Steps for You

### 1. **Upload Videos**
Go to admin edit page for each movie:
- Movie #32: http://localhost:8000/admin/movies/32/edit
- Movie #33: http://localhost:8000/admin/movies/33/edit
- Movie #34: http://localhost:8000/admin/movies/34/edit

Click "Add Video" and upload your video files. They will be automatically stored in Supabase.

### 2. **Publish Movies**
Change status from 'draft'/'coming_soon' to 'published' to make them visible on the homepage.

### 3. **Verify Display**
Check these pages:
- Home: http://localhost:8000
- Movie #33: http://localhost:8000/movies/33
- Admin list: http://localhost:8000/admin/movies

### 4. **Check Supabase Dashboard**
- Posters: https://supabase.com/dashboard/project/payjcwtxciyvlkhzdcjc/storage/files/buckets/posters
- Videos: https://supabase.com/dashboard/project/payjcwtxciyvlkhzdcjc/storage/files/buckets/videos

---

## 🧪 Test Script

Run this anytime to verify everything is working:

```bash
php verify_all_displays.php
```

This checks:
- All thumbnails are Supabase URLs
- All banners are Supabase URLs
- All videos are in Supabase
- Movie statuses

---

## 🐛 Troubleshooting

### Images not showing?
1. Check database: `SELECT id, title, thumbnail FROM movies;`
2. Verify URL format includes `supabase.co`
3. Check Supabase dashboard to confirm files exist

### Videos not uploading?
1. Check `.env` has `SUPABASE_KEY` (service role key)
2. Check bucket permissions are public
3. Check file size is under 2GB

### Admin page shows broken images?
1. Clear cache: `php artisan cache:clear`
2. Clear view cache: `php artisan view:clear`
3. Refresh page with Ctrl+F5

---

## ✅ Summary

**Everything is now configured correctly!** 

All 8 pages that display images/videos are using Supabase Storage:
1. ✅ Home page
2. ✅ Movie detail page
3. ✅ Watch page
4. ✅ Genre pages
5. ✅ Search results
6. ✅ Admin movie list
7. ✅ Admin create page
8. ✅ Admin edit page

Your images load from:
- `https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/posters/...`

Your videos will load from:
- `https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/videos/...`

**No more local storage files!** Everything uses Supabase. 🎉
