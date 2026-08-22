# Kun Online Movie Platform

A modern Laravel-based online movie streaming platform with Supabase storage integration.

## Features

- 🎬 Movie Management (Create, Read, Update, Delete)
- 🎥 Video Streaming from Supabase Storage
- 🖼️ Image hosting via Supabase
- 👥 User Authentication & Authorization
- 🎭 Genre-based categorization
- ⭐ Movie ratings and reviews
- 📊 Admin Dashboard
- 🔍 Search & Filter functionality
- 📱 Responsive Netflix-style UI

## Tech Stack

- **Backend:** Laravel 11.x
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL
- **Storage:** Supabase Storage
- **Authentication:** Laravel Breeze

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL
- Supabase Account

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd kun
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up your `.env` file with:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SUPABASE_URL=your_supabase_url
SUPABASE_KEY=your_service_role_key
```

5. Run migrations and seeders
```bash
php artisan migrate --seed
```

6. Build assets
```bash
npm run build
```

7. Start the development server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Admin Access

Default admin credentials (change after first login):
- Email: `admin@kun.com`
- Password: `password`

Admin panel: `http://localhost:8000/admin`

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/           # Admin dashboard controllers
│   ├── Auth/            # Authentication controllers
│   └── ...              # Frontend controllers
├── Models/              # Eloquent models
└── Services/            # Business logic services
    ├── SupabaseStorageService.php
    ├── VideoServiceV2.php
    └── MovieService.php

resources/
├── views/
│   ├── admin/           # Admin panel views
│   ├── movies/          # Movie display views
│   └── layouts/         # Layout templates
└── css/                 # Styles

database/
├── migrations/          # Database migrations
└── seeders/             # Database seeders
```

## Usage

### Creating Movies

1. Login to admin panel
2. Navigate to Movies > Create New
3. Fill in movie details
4. Upload thumbnail and banner images (saved to Supabase)
5. Upload video files (saved to Supabase)
6. Set status to "Published"

### Managing Videos

Videos are automatically stored in Supabase Storage:
- Bucket: `videos/videos/{movie_id}/`
- Supported formats: MP4, WebM, OGG
- Max file size: 2GB

### Managing Images

Images are automatically stored in Supabase Storage:
- Bucket: `posters/posters/`
- Supported formats: JPG, PNG, WebP
- Max file size: 10MB (thumbnails), 20MB (banners)

## License

This project is proprietary software.

## Support

For issues or questions, contact the development team.
