# 🚀 Quick Start Guide - View Your KUN Admin Dashboard

## 📋 Step-by-Step Instructions

### Step 1: Open PowerShell
Press `Win + X` and select "Windows PowerShell" or "Terminal"

### Step 2: Navigate to Your Project
```powershell
cd e:\NU_year3\NU3_semester2\Development_Research\Onilen_Movie_Project\kun
```

### Step 3: Create Admin Account (First Time Only)
```powershell
php create-admin.php
```

**Just press Enter to use defaults:**
- Name: Admin KUN
- Email: admin@kun.com
- Password: admin123

### Step 4: Start Laravel Server
```powershell
php artisan serve
```

You'll see:
```
Starting Laravel development server: http://127.0.0.1:8000
```

**✅ Keep this window open!** Don't close it while using the dashboard.

### Step 5: Open Your Browser

Open any browser (Chrome, Edge, Firefox) and visit:

```
http://127.0.0.1:8000/admin/dashboard
```

### Step 6: Login

If you're not already logged in, login with:
- **Email:** `admin@kun.com`
- **Password:** `admin123`

---

## 🎯 What You'll See

Your admin dashboard will show:

### 📊 Top Section - Statistics Cards
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ Total Movies│ Total Users │ Total Views │   Revenue   │
│     150     │    1,234    │   45,678    │  $12,345    │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

### 📈 Middle Section - Charts
- **User Growth Chart:** Line chart showing user registration trends
- **Revenue Chart:** Bar chart showing weekly revenue

### 📋 Bottom Section - Data Tables
- **Popular Movies:** Top 10 most viewed movies with thumbnails
- **Recent Users:** Latest registered users with avatars
- **Recent Payments:** Latest payment transactions

### ⚡ Quick Actions
Six colorful buttons for common tasks:
- Add New Movie
- Create Genre
- Manage Users
- View Payments
- View Reports
- Export Data

---

## 🎨 Design Preview

```
┌────────────────────────────────────────────────────────────┐
│  [☰] KUN Admin Panel          🔍 Search    🔔 👤 Admin     │
├────────────────────────────────────────────────────────────┤
│                                                             │
│  📊 Dashboard Overview                    [🔄 Refresh]     │
│  Welcome back, Admin! Here's what's happening...           │
│                                                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐     │
│  │ 🎬 Movies│ │ 👥 Users │ │ 👁 Views │ │ 💰 Revenue│     │
│  │   150    │ │  1,234   │ │  45,678  │ │ $12,345  │     │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘     │
│                                                             │
│  ┌──────────────────────┐ ┌──────────────────────┐        │
│  │  📈 User Growth      │ │  📊 Revenue Chart    │        │
│  │  [Line Chart Here]   │ │  [Bar Chart Here]    │        │
│  └──────────────────────┘ └──────────────────────┘        │
│                                                             │
│  ┌────────────────────────────────────────┐                │
│  │  🔥 Top 10 Popular Movies              │                │
│  │  #1 [🎬] Movie Title    👁 1,234  ⭐ 4.5│                │
│  │  #2 [🎬] Another Movie  👁 1,100  ⭐ 4.3│                │
│  └────────────────────────────────────────┘                │
│                                                             │
└────────────────────────────────────────────────────────────┘
```

---

## 🔧 Troubleshooting

### ❌ Problem: "Connection refused" or page won't load

**Solution:**
1. Make sure Laravel server is running (Step 4)
2. Check the PowerShell window - you should see server running
3. Try this URL instead: `http://localhost:8000/admin/dashboard`

---

### ❌ Problem: "Access Denied" or 403 Error

**Solution:**
You need an admin account. Run Step 3 to create one.

---

### ❌ Problem: Database errors

**Solution:**
```powershell
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

---

### ❌ Problem: Page loads but no data showing

**Solution:**
Your database is empty. You need to add some test data:
```powershell
php artisan db:seed
```

---

## 📱 Mobile View

The dashboard is fully responsive! You can also view it on:
- Your phone (use your computer's IP address)
- Tablet
- Any device on your local network

To access from another device:
1. Find your computer's IP (run `ipconfig` in PowerShell)
2. Use: `http://YOUR_IP:8000/admin/dashboard`

---

## 🎯 Quick Command Reference

```powershell
# Create admin account
php create-admin.php

# Start server
php artisan serve

# Stop server
Press Ctrl+C in the PowerShell window

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
```

---

## 🌐 Access URLs

| Page | URL |
|------|-----|
| **Admin Dashboard** | `http://127.0.0.1:8000/admin/dashboard` |
| Login | `http://127.0.0.1:8000/login` |
| Public Homepage | `http://127.0.0.1:8000` |
| Movies Management | `http://127.0.0.1:8000/admin/movies` |
| Users Management | `http://127.0.0.1:8000/admin/users` |
| Genres Management | `http://127.0.0.1:8000/admin/genres` |

---

## 💡 Pro Tips

1. **Keep Server Running:** Don't close the PowerShell window while using the dashboard
2. **Bookmark It:** Save the admin dashboard URL in your browser
3. **Auto-login:** Check "Remember Me" when logging in
4. **Multiple Tabs:** You can open multiple admin pages in different tabs
5. **Refresh Data:** Click the "Refresh" button to update statistics

---

## 🎉 Success Checklist

- [ ] PowerShell is open in project directory
- [ ] Admin account created (email: admin@kun.com)
- [ ] Laravel server is running
- [ ] Browser opened to admin dashboard URL
- [ ] Logged in successfully
- [ ] Dashboard is showing statistics and charts

---

## 📞 Still Need Help?

If you're still having issues:

1. **Check Laravel Logs:**
   ```
   storage/logs/laravel.log
   ```

2. **Check .env File:**
   Make sure database settings are correct

3. **Restart Everything:**
   ```powershell
   # Stop server (Ctrl+C)
   php artisan config:clear
   php artisan cache:clear
   php artisan serve
   ```

---

**🎬 Enjoy your KUN Movie Admin Dashboard!** 🍿

You're now ready to manage movies, users, and view statistics in style! 🚀
