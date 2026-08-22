# 📹 Video Storage Explanation

## 🗄️ Database Table: `movie_videos`

This table stores information about all videos associated with movies.

### Table Structure:

| Column | Type | Description | Example |
|--------|------|-------------|---------|
| **id** | bigint | Primary key (auto-increment) | 1, 2, 3... |
| **movie_id** | bigint | Foreign key to `movies` table | 5 (links to movie with id=5) |
| **title** | string | Video title | "Main Movie", "Official Trailer" |
| **video_url** | string | Full URL to video file | `https://xxx.supabase.co/storage/v1/object/public/videos/1/movie_1_1724123456_abc123.mp4` |
| **video_type** | enum | Type of video | `movie`, `trailer`, `teaser`, `behind_scenes`, `clip` |
| **quality** | string (nullable) | Video quality | `480p`, `720p`, `1080p`, `4K` |
| **duration** | integer (nullable) | Duration in seconds | 7200 (= 2 hours) |
| **file_size** | integer (nullable) | File size in MB | 850 (= 850 MB) |
| **is_primary** | boolean | Is this the main video? | `true` or `false` |
| **created_at** | timestamp | When record was created | 2026-08-20 10:30:00 |
| **updated_at** | timestamp | When record was updated | 2026-08-20 10:30:00 |

---

## 📊 How Video Storage Works

### Option 1: Upload Video File (Recommended for large files)

```
1. User uploads video file through form
   ↓
2. Laravel stores file in Supabase Storage
   Path: videos/{movie_id}/movie_{movie_id}_{timestamp}_{random}.mp4
   ↓
3. Laravel gets the public URL from Supabase
   URL: https://xxx.supabase.co/storage/v1/object/public/videos/...
   ↓
4. Laravel saves video info to movie_videos table:
   - movie_id: 5
   - title: "Main Movie"
   - video_url: "https://xxx.supabase.co/storage/..."
   - video_type: "movie"
   - quality: "1080p" (auto-detected or manual)
   - duration: 7200 (auto-detected if possible)
   - file_size: 850 (calculated from uploaded file)
   - is_primary: true
```

### Option 2: Provide Video URL (For external hosting)

```
1. User provides external video URL
   Example: https://example.com/videos/my-movie.mp4
   ↓
2. Laravel validates the URL
   ↓
3. Laravel saves video info to movie_videos table:
   - movie_id: 5
   - title: "Main Movie"
   - video_url: "https://example.com/videos/my-movie.mp4"
   - video_type: "movie"
   - quality: null (cannot auto-detect from URL)
   - duration: null (cannot auto-detect from URL)
   - file_size: null (cannot auto-detect from URL)
   - is_primary: true
```

---

## 🔗 Database Relationships

```
movies (table)
├── id: 1
├── title: "The Dark Knight"
├── thumbnail: "movies/thumbnails/xyz.jpg"
└── banner: "movies/banners/abc.jpg"
    │
    └── movie_videos (table) ← Related videos
        ├── id: 1
        ├── movie_id: 1 ← Links to movie
        ├── title: "Main Movie"
        ├── video_url: "https://...supabase.../video.mp4"
        ├── video_type: "movie"
        ├── is_primary: true
        │
        ├── id: 2
        ├── movie_id: 1 ← Same movie
        ├── title: "Official Trailer"
        ├── video_url: "https://...supabase.../trailer.mp4"
        ├── video_type: "trailer"
        └── is_primary: false
```

---

## 📝 Example Data

### Movie Record (movies table):
```json
{
  "id": 1,
  "title": "The Odyssey",
  "slug": "the-odyssey",
  "status": "published",
  "thumbnail": "movies/thumbnails/odyssey_thumb.jpg",
  "banner": "movies/banners/odyssey_banner.jpg",
  "created_at": "2026-08-20 10:30:00"
}
```

### Video Record (movie_videos table):
```json
{
  "id": 1,
  "movie_id": 1,
  "title": "Main Movie",
  "video_url": "https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/videos/1/movie_1_1724123456_abc123.mp4",
  "video_type": "movie",
  "quality": "1080p",
  "duration": 5400,
  "file_size": 850,
  "is_primary": true,
  "created_at": "2026-08-20 10:30:00"
}
```

### Trailer Record (movie_videos table):
```json
{
  "id": 2,
  "movie_id": 1,
  "title": "Official Trailer",
  "video_url": "https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/videos/1/trailer_1_1724123500_def456.mp4",
  "video_type": "trailer",
  "quality": "720p",
  "duration": 120,
  "file_size": 12,
  "is_primary": false,
  "created_at": "2026-08-20 10:31:00"
}
```

---

## 🎯 Video Types

| Type | Description | Use Case |
|------|-------------|----------|
| **movie** | Full movie video | Main content users watch |
| **trailer** | Movie trailer | Preview/promotional video |
| **teaser** | Short teaser | Brief promotional clip |
| **behind_scenes** | Behind the scenes footage | Extra content |
| **clip** | Short clip from movie | Highlights/excerpts |

---

## 🔍 How to Query Videos

### Get all videos for a movie:
```php
$movie = Movie::find(1);
$videos = $movie->videos; // Returns all videos
```

### Get only the main movie video:
```php
$mainVideo = $movie->videos()
    ->where('video_type', 'movie')
    ->where('is_primary', true)
    ->first();
```

### Get trailer:
```php
$trailer = $movie->videos()
    ->where('video_type', 'trailer')
    ->first();
```

### Get videos by quality:
```php
$hdVideos = $movie->videos()
    ->where('quality', '1080p')
    ->get();
```

---

## 📦 Storage Locations

### Physical Storage:
- **File Uploads**: Stored in **Supabase Storage** (cloud)
- **Thumbnails/Banners**: Stored in `storage/app/public/movies/` (local)

### Database Storage:
- **Video metadata**: Stored in `movie_videos` table
- **Movie info**: Stored in `movies` table

### URL Format:
```
Supabase Storage URL:
https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/videos/{movie_id}/{filename}

Example:
https://payjcwtxciyvlkhzdcjc.supabase.co/storage/v1/object/public/videos/1/movie_1_1724123456_abc123.mp4
```

---

## ✅ Summary

1. **Videos are stored in TWO places:**
   - 📁 **File itself**: Supabase Storage (cloud) or External URL
   - 🗄️ **Metadata**: `movie_videos` database table

2. **One movie can have multiple videos:**
   - Main movie (required)
   - Trailer (optional)
   - Teasers, clips, etc. (optional)

3. **The `is_primary` flag** indicates which video is the main one to play

4. **Video URL** is the key field - it's the actual link to watch the video

5. **When you upload a video:**
   - File → Supabase Storage
   - URL + metadata → `movie_videos` table
   - Linked to movie via `movie_id`
