<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# KUN - Online Movie Streaming Platform

A Laravel-based movie streaming platform with user authentication, role-based access control (RBAC), admin dashboard, favorites, watchlists, and payment integration.

## Project Information

- **Frontend:** Blade templates, Vite, CSS, JavaScript
- **Navigation:** Laravel Routes (`routes/web.php`)
- **Backend:** Laravel PHP, Controllers, Eloquent ORM
- **Database:** MySQL / PostgreSQL (Laravel Migrations)
- **Authentication:** Laravel Auth, custom RegisterController, LoginController, LogoutController
- **Files/Images:** Laravel Storage (local/public disk)
- **Payments:** PaymentController (subscription/checkout/process)
- **Notifications:** Laravel session-based flash messages
- **Web Hosting:** Local development server (`php artisan serve`)

## Environment Details

- **Current time:** 2026-08-16T19:26:38+07:00
- **Working directory:** `E:\NU_year3\NU3_semester2\Development_Research\Onilen_Movie_Project\kun`
- **Workspace root:** `E:\NU_year3\NU3_semester2\Development_Research\Onilen_Movie_Project`
- **Platform:** Windows (win32)
- **Default shell:** PowerShell 5.1
- **Active file:** `KUN_LOGIN_FLOW_IMPLEMENTATION.md`

## Features

- Public movie browsing and search
- User registration and login
- Role-based access control (Admin, User, Moderator)
- Admin dashboard for managing movies, genres, users, roles, and permissions
- User watch history and continue watching
- Favorites and watchlists
- Subscription plans and payment processing
- Email verification
- Social login (Google, Facebook)
- Password reset

## Getting Started

1. Install dependencies: `composer install && npm install`
2. Copy `.env.example` to `.env` and configure database
3. Generate app key: `php artisan key:generate`
4. Run migrations: `php artisan migrate --seed`
5. Serve: `php artisan serve`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
